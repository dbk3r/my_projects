#!/usr/bin/python

from shutil import copyfile
from subprocess import Popen
import sys,locale
from time import gmtime, strftime
locale.setlocale(locale.LC_ALL, 'de_DE.UTF-8')
currDate = strftime("%A", gmtime())
src = sys.argv[1]
p = Popen(['cp','-p','--preserve',src,src + "-" + currDate])
p.wait()
