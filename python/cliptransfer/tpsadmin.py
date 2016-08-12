#!/usr/bin/python

import os, sys, time

from db_functions import *

mysql_connection = mysql_connect("sp-tpsdock01", 3900, "adm_mon", "adm_mon", "adm")
mysql_table_setup(mysql_connection)


if len(sys.argv) >=3 :
    command = sys.argv[1]


    if command == "copy":
        dest = sys.argv[3]
        src = sys.argv[2]
        try:
            copyFilesRecursive(mysql_connection, src, dest, 0)
        except KeyboardInterrupt:
            print "Operation canceled!"

    elif command == "move":
        dest = sys.argv[3]
        src = sys.argv[2]
        #copyFilesRecursive(mysql_connection, src, dest, 1)

    elif command == "cleanup":
        days = sys.argv[3]
        src = sys.argv[2]
        cleanup(mysql_connection, src, days)

    else:
        helpme()
else:
    helpme()


mysql_disconnect(mysql_connection)
