#!/usr/bin/env python
'''
LevelDB Shell
Copyright(c) 2012 Shaun Li <shonhen@gmail.com>
MIT Licensed
'''
from leveldb import LevelDB, WriteBatch
import re
import sys

class UsageException(Exception):
  pass

class LevelDBShell:
  def __init__(self, dbfolder):
    self._db = LevelDB(dbfolder)

  def db(self):
    return self._db

  def actionKeys(self, pattern = None):
    for key, value in self.db().RangeIter():
      if pattern is None:
        print key
      else:
        if re.match(r'^%s$' % pattern, key):
          print key

  def actionGet(self, key):
    print self.db().Get(key)

  def actionSet(self, key, value):
    self.db().Put(key, value)

  def actionDelete(self, key):
    self.db().Delete(key)

  @staticmethod
  def run(args):
    if len(args) == 0:
      raise UsageException()

    dbfolder, command, arguments = None, None, []
    while len(args) > 0:
      arg = args.pop(0)
      if arg == '-d':
        dbfolder = args.pop(0)
      else:
        if command is None:
          command = arg
        else:
          arguments.append(arg)

    if dbfolder is None:
      raise UsageException('Database folder is missing')

    shell = LevelDBShell(dbfolder)
    method = 'action%s%s' % (command[0].upper(), command[1:])
    if not hasattr(shell, method):
      raise UsageException('Unknown command - %s' % command)

    method = getattr(shell, method)
    method(*arguments)

if __name__ == '__main__':
  try:
    LevelDBShell.run(sys.argv[1:])
  except UsageException, e:
    if e.message:
      print e.message
    print '''Usage:
  ldb -d <folder> <command> [<args>]

The most commonly used commands are:
  keys [pattern]
  set <key> <value>
  get <key>
  delete <key>
'''
