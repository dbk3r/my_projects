#!/usr/bin/python

import os, sys, time
import signal
from db_functions import *


mysql_server = getconfig("tpsadm.cfg", "mysql", "server")
mysql_db = getconfig("tpsadm.cfg", "mysql", "db")
mysql_port = int(getconfig("tpsadm.cfg", "mysql", "port"))
mysql_user = getconfig("tpsadm.cfg", "mysql", "user")
mysql_passwd = getconfig("tpsadm.cfg", "mysql", "passwd")

mysql_connection = mysql_connect(mysql_server, mysql_port, mysql_db, mysql_user, mysql_passwd)
mysql_table_setup(mysql_connection)

main_mountpoint = getconfig("tpsadm.cfg", "mountpoints", "main")
backup_mountpoint = getconfig("tpsadm.cfg", "mountpoints", "backup")

service = sys.argv[1]
command = sys.argv[2]

allowed_extensions = []
if service == "ct_Standbilder":
    allowed_extensions = ['tiff']
elif service == "ct_Videos":
    allowed_extensions = ['mp4', 'mxf']

signal.signal(signal.SIGINT, handler)
signal.signal(signal.SIGTERM, handler)

try:
    while True:
        mysql_update(mysql_connection, "ct_service", "service_state", "4", "service", service)
        if len(sys.argv) >= 3:

            if command == "copy":
                dest = sys.argv[4]
                src = sys.argv[3]
                mountpoint = mysql_select(mysql_connection, "select active_mountpoint from ct_failover where id=1")
                destination = mountpoint[0][0] + "/" + dest
                failover(mysql_connection, dest, main_mountpoint, backup_mountpoint)
                copyFilesRecursive(mysql_connection, src, destination, 0, allowed_extensions)

            elif command == "move":
                dest = sys.argv[4]
                src = sys.argv[3]
                # copyFilesRecursive(mysql_connection, src, dest, 1, allowed_extensions)

            elif command == "active":
                set_active_mountpoint(mysql_connection, sys.argv[3])
                break

            elif command == "cleanup":
                days = sys.argv[4]
                src = sys.argv[3]
                cleanup(mysql_connection, src, days)
                break

            elif command == "dbcleanup":
                days = sys.argv[3]
                mysql_dbcleanup(mysql_connection, days)
                break

            else:
                helpme()
                break
        else:
            helpme()
            break


        time.sleep(4)
finally:
    prepareExit(mysql_connection, service)
    print "exit"