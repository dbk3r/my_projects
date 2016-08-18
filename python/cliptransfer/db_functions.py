import MySQLdb
import sys, os, shutil
import time
import ConfigParser


def getconfig(configFile, section, option):
    config = ConfigParser.ConfigParser()
    config.read(configFile)
    value = config.get(section, option)
    return value


def helpme():
    print "tpsadmin.py [SERVICE <ct_Standbilder|ct_Videos|ct_cleanup>] [active|copy|move|cleanup|dbcleanup] <src-folder> <dest-folder|days>"
    print ""
    print "EXAMPLES: "
    print ""
    print "start service ct_Standbilder : python tpsadmin.py ct_Standbilder copy /mnt/sp-fbp-ist01/Standbilder Standbilder (/etc/init.d/ct_Stanbilder start)"
    print "file cleanup                 : python tpsadmin.py ct_cleanup cleanup /mnt/sp-fbp-ist01/Standbilder 7 (will delete all files in given mountpoint, older than 7 days)"
    print "db cleanup                   : python tpsadmin.py adm dbcleanup 7  (will delete all DB Rows older than 7 days)"
    print "set active mountpoint        : python tpsadmin.py adm active /mnt/sb-isis01"


def get_extension(filename):
    basename = os.path.basename(filename)  # os independent
    ext = '.'.join(basename.split('.')[1:])
    return ext if ext else None


def copyFilesRecursive(con, src, dest, deleteSrc, allowed_extensions):

    if os.path.isdir(src):
        if not os.path.isdir(dest):
            os.makedirs(dest)

        files = os.listdir(src)
        for f in files:
            copyFilesRecursive(con, os.path.join(src, f), os.path.join(dest, f), deleteSrc, allowed_extensions)
    else:
        ch = mysql_check_entry(con, "ct_log", "file", src)
        try:
            if not ch and get_extension(src) in allowed_extensions:
                print "copy file " + src + " to " + dest
                shutil.copyfile(src, dest)
                shutil.copystat(src, dest)

        except shutil.Error as e:
            if con:
                mysql_insert(con, "ct_log", src, 1, e)
            print "Error: %s" % e

        except IOError as e:
            print "Error %s" % e.strerror
            mysql_insert(con, "ct_log", src, 1, e.strerror)
        except OSError as e:
            print "Error %s" % e.strerror
            mysql_insert(con, "ct_log", src, 1, e.strerror)
        finally:
            if not ch and get_extension(src) in allowed_extensions:
                mysql_insert(con, "ct_log", src, 0, "copied successfully")

        if deleteSrc == 1:
            try:
                os.remove(src)
            except IOError as e:
                print "Error %s" % e.strerror


def set_active_mountpoint(con, mountpoint):
    mysql_update(con, "ct_failover", "active_mountpoint", mountpoint, "id", "1")


def failover(con, dest, main, backup):
    active_mountpoint = mysql_select(con, "select active_mountpoint from ct_failover where id='1'")
    if not os.path.isdir(active_mountpoint[0][0] + "/" + dest + "/BA"):
        print active_mountpoint[0][0] + " not available"
        if active_mountpoint[0][0] == main:
            print "set " + backup + " active"
            set_active_mountpoint(con, backup)
        elif active_mountpoint[0][0] == backup:
            print "set " + main + " active"
            set_active_mountpoint(con, main)


def cleanup(con, folder, d):
    i = 0
    for root, directories, filenames in os.walk(folder):
        now = time.time()
        cutoff = now - (int(d) * 86400)

        for filename in filenames:

            file = os.path.join(root, filename)
            st = os.stat(file)
            if st.st_mtime < cutoff:

                try:
                    print "deleting " + file
                    os.remove(file)
                    i += 1
                except OSError as e:
                    print "Error %s" % e.strerror
                    mysql_insert(con, "ct_log", file, 1, e.strerror)

                except IOError as e:
                    print "Error %s" % e.strerror
                    mysql_insert(con, "ct_log", file, 1, e.strerror)

                finally:
                    mysql_insert(con, "ct_log", file, 2, "deleted successfully")

    print str(i) + " deleted files"


def mysql_connect(mysql_server, mysql_port, mysql_db, mysql_user, mysql_pass):
    con = []
    try:
        con = MySQLdb.connect(host=mysql_server, port=mysql_port, user=mysql_user, passwd=mysql_pass, db=mysql_db)

    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
        sys.exit(1)

    finally:
        if con:
            return con

def mysql_dbcleanup(con, days):
    try:
        cursor = con.cursor()
        cursor.execute("delete from ct_log where ts < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL " + str(days) + " DAY))")
        con.commit()
        cursor.close()
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])

def mysql_disconnect(con):
    con.close()


def mysql_update(con, table, col, value, where_col, where_value):

    try:
        cursor = con.cursor()
        cursor.execute("update " + table + " set " + col + "='" + value + "' where " + where_col + "='" + where_value + "'")
        con.commit()
        cursor.close()

    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
    except MySQLdb.ProgrammingError, e:
        print "mein - Error %d: %s" % (e.args[0], e.args[1])


def mysql_select(con, statement):

    try:
        cursor = con.cursor()
        cursor.execute(statement)
        results = cursor.fetchall()
        cursor.close()
    finally:
        return results


def mysql_check_entry(con, table, col, search):
    c = []
    stmt = "select " + col + " from " + table + " where file_state=0 AND " + col + " like '%" + search + "%'"

    try:
        if con:
            c = con.cursor()
        c.execute(stmt)
        result = c.fetchone()
        c.close()
        if result:
            return True
        else:
            return False

    except MySQLdb.ProgrammingError, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])


def mysql_check_if_table_exists (con, table):
    stmt = "SHOW TABLES LIKE '" + table + "'"
    c = con.cursor()
    c.execute(stmt)
    result = c.fetchone()
    c.close()
    if result:
        return True
    else:
        return False


def mysql_table_setup(con):

    c = con.cursor()
    create_finished = "CREATE TABLE IF NOT EXISTS `ct_log` (`ID` int(11) unsigned NOT NULL auto_increment,`ts` varchar(255), `file` MEDIUMTEXT , `file_state` tinyint(1), `message` MEDIUMTEXT, PRIMARY KEY  (`ID`))"
    create_ct_states = "CREATE TABLE IF NOT EXISTS `ct_states` (`ID` int(11) unsigned NOT NULL auto_increment,`state_description` varchar(255), `state` tinyint(1), PRIMARY KEY  (`ID`))"
    create_ct_failover = "CREATE TABLE IF NOT EXISTS `ct_failover` (`ID` int(11) unsigned NOT NULL auto_increment,`active_mountpoint` varchar(255), PRIMARY KEY  (`ID`))"
    create_ct_service = "CREATE TABLE IF NOT EXISTS `ct_service` (`ID` int(11) unsigned NOT NULL auto_increment, `ts` varchar(255), `service` varchar(255) NOT NULL default '', `service_state` tinyint(1), PRIMARY KEY  (`ID`))"
    create_ct_mon = "CREATE TABLE IF NOT EXISTS `ct_mon` (`ID` int(11) unsigned NOT NULL auto_increment, `ts` varchar(255), `file` MEDIUMTEXT, `file_state` tinyint(1), `file_op_error` MEDIUMTEXT, PRIMARY KEY  (`ID`))"
    try:
        if not mysql_check_if_table_exists(con, "ct_log"):
            c.execute(create_finished)
        if not mysql_check_if_table_exists(con, "ct_service"):
            c.execute(create_ct_service)
        if not mysql_check_if_table_exists(con, "ct_mon"):
            c.execute(create_ct_mon)
        if not mysql_check_if_table_exists(con, "ct_failover"):
            c.execute(create_ct_failover)
            ct_states = "INSERT INTO `ct_failover` (`active_mountpoint`) VALUES ('" + getconfig("tpsadm.cfg", "mountpoints", "main") + "')"
            c.execute(ct_states)
            con.commit()
        if not mysql_check_if_table_exists(con, "ct_states"):
            c.execute(create_ct_states)
            ct_states = "INSERT INTO `ct_states` (`state_description`, `state`) VALUES ('OK', 0 )"
            c.execute(ct_states)
            ct_states = "INSERT INTO `ct_states` (`state_description`, `state`) VALUES ('FAILED', 1 )"
            c.execute(ct_states)
            ct_states = "INSERT INTO `ct_states` (`state_description`, `state`) VALUES ('DELETED', 2 )"
            c.execute(ct_states)
            ct_states = "INSERT INTO `ct_states` (`state_description`, `state`) VALUES ('RUNNING', 3 )"
            c.execute(ct_states)
            ct_states = "INSERT INTO `ct_states` (`state_description`, `state`) VALUES ('STOPPED', 4 )"
            c.execute(ct_states)
            con.commit()


    except MySQLdb.Error, e:
        print ""
        # print "Error %d: %s" % (e.args[0], e.args[1])
        # sys.exit(1)

    c.close()


def mysql_insert(con, table, filename, file_state, message):

    if con:
        try:
            timestamp = int(time.time())
            statement = "INSERT INTO `" + table +"` (`ts`, `file`, `file_state`, `message`) VALUES ('" + str(timestamp) + "', '" + filename + "', '" + str(file_state) + "', '" + str(message) + "')"
            c = con.cursor()
            c.execute(statement)
            con.commit()
            c.close()


        except MySQLdb.Error, e:
            print "Error %d: %s" % (e.args[0], e.args[1])
        except MySQLdb.ProgrammingError, e:
            print "Error %d: %s" % (e.args[0], e.args[1])

def handler(signum,frame):
    sys.exit(0)

def prepareExit(con,service):
    mysql_update(con, "ct_service", "service_state", "5", "service", service)
    mysql_disconnect(con)