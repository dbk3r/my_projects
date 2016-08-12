import MySQLdb
import sys, os, shutil
import time


def helpme():
    print "tpsadmin.py [copy|move|delete] <src-folder> <dest-folder|days>"


def copyFilesRecursive(con, src, dest, deleteSrc):

    if os.path.isdir(src):
        if not os.path.isdir(dest):
            print "mkdir: " + dest
            os.makedirs(dest)

        files = os.listdir(src)
        for f in files:
            copyFilesRecursive(con, os.path.join(src, f), os.path.join(dest, f), deleteSrc)
    else:

        try:
            if not mysql_check_entry(con, "ct_log", "file", src):
                print "copy file " + src + " to " + dest
                shutil.copyfile(src, dest)

        except shutil.Error as e:
            mysql_insert(con, "ct_log", src, 1, e)
            print "Error: %s" % e

        except IOError as e:
            print "Error %s" % e.strerror
            mysql_insert(con, "ct_log", src, 1, e.strerror)

        except KeyboardInterrupt:
            print "copy process canceled!"
            mysql_disconnect(con)
            exit(1)

        finally:
            if not mysql_check_entry(con, "ct_log", "file", src):
                try:
                    mysql_insert(con, "ct_log", src, 0, "copied successfully")
                except MySQLdb.Error, e:
                    print "Error %d: %s" % (e.args[0], e.args[1])
        if deleteSrc == 1:
            try:
                os.remove(src)
            except IOError as e:
                print "Error %s" % e.strerror


def cleanup(con, folder, d):
    i=0
    for root, directories, filenames in os.walk(folder):
        now = time.time()
        cutoff = now - (int(d) * 86400)

        for filename in filenames:

            file = os.path.join(root, filename)
            st = os.stat(file)
            if st.st_mtime < cutoff:
                i += 1
                try:
                    print "deleting " + file
                    os.remove(file)
                except IOError as e:
                    print "Error %s" % e.strerror
                except KeyboardInterrupt:
                    print "deletion process canceled!"

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


def mysql_disconnect(con):
    con.close()


def mysql_select(con,statement):
    cursor = con.cursor()
    cursor.execute(statement)
    results = cursor.fetchall()
    return results

def mysql_check_entry(con, table, col, search):
    stmt = "select " + col + " from " + table + " where file_state=0 AND " + col + " like '%" + search + "%'"
    c = con.cursor()
    try:
        c.execute(stmt)
        result = c.fetchone()
        if result:
            return True
        else:
            return False
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
    except KeyboardInterrupt:
        print "check_entry process canceled!"

def mysql_check_if_table_exists (con, table):
    stmt = "SHOW TABLES LIKE '" + table + "'"
    c = con.cursor()
    c.execute(stmt)
    result = c.fetchone()
    if result:
        return True
    else:
        return False


def mysql_table_setup(con):

    c = con.cursor()
    create_finished = "CREATE TABLE IF NOT EXISTS `ct_log` (`ID` int(11) unsigned NOT NULL auto_increment,`ts` varchar(255), `file` MEDIUMTEXT , `file_state` tinyint(1), `message` MEDIUMTEXT, PRIMARY KEY  (`ID`))"
    create_ct_states = "CREATE TABLE IF NOT EXISTS `ct_states` (`ID` int(11) unsigned NOT NULL auto_increment,`state_description` varchar(255), `state` tinyint(1), PRIMARY KEY  (`ID`))"
    create_ct_service = "CREATE TABLE IF NOT EXISTS `ct_service` (`ID` int(11) unsigned NOT NULL auto_increment, `ts` varchar(255), `service` varchar(255) NOT NULL default '', `service_state` tinyint(1), PRIMARY KEY  (`ID`))"
    create_ct_mon = "CREATE TABLE IF NOT EXISTS `ct_mon` (`ID` int(11) unsigned NOT NULL auto_increment, `ts` varchar(255), `file` MEDIUMTEXT, `file_state` tinyint(1), `file_op_error` MEDIUMTEXT, PRIMARY KEY  (`ID`))"
    try:
        if not mysql_check_if_table_exists(con, "ct_log"):
            c.execute(create_finished)
        if not mysql_check_if_table_exists(con, "ct_service"):
            c.execute(create_ct_service)
        if not mysql_check_if_table_exists(con, "ct_mon"):
            c.execute(create_ct_mon)
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

def mysql_insert(con, table, filename, file_state, message):

    try:
        timestamp = int(time.time())
        statement = "INSERT INTO `" + table +"` (`ts`, `file`, `file_state`, `message`) VALUES ('" + str(timestamp) + "', '" + filename + "', '" + str(file_state) + "', '" + str(message) + "')"
        c = con.cursor()
        c.execute(statement)
        con.commit()

    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])