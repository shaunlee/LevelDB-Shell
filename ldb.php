#!/usr/bin/env php
<?php
/**
 * LevelDB Shell
 * Copyright(c) 2012 Shaun Li <shonhen@gmail.com>
 * MIT Licensed
 */

class UsageException extends Exception {
}

class LevelDBShell {

  private $_db;

  public function __construct($dbfolder) {
    $this->_db = new LevelDB($dbfolder);
  }

  protected function db() {
    return $this->_db;
  }

  public function actionKeys($pattern = '') {
    $iter = new LevelDBIterator($this->db());
    foreach ($iter as $key => $value) {
      if (!$pattern)
        echo $key . PHP_EOL;
      if (preg_match('/^' . $pattern . '$/', $key))
        echo $key . PHP_EOL;
    }
  }

  public function actionGet($key) {
    echo $this->db()->get($key) . PHP_EOL;
  }

  public function actionSet($key, $value) {
    $this->db()->set($key, $value);
  }

  public function actionDelete($key) {
    $this->db()->delete($key);
  }

  public static function run($args) {
    if (empty($args))
      throw new UsageException;

    $dbfolder = null;
    $command = null;
    $arguments = array();

    while (!empty($args)) {
      $arg = array_shift($args);
      switch ($arg) {
      case '-d':
        $dbfolder = array_shift($args);
        break;
      default:
        if (!$command)
          $command = $arg;
        else
          $arguments[] = $arg;
        break;
      }
    }

    if (!$dbfolder)
      throw new UsageException('Database folder is missing');

    $shell = new LevelDBShell($dbfolder);
    $method = 'action' . ucfirst($command);
    if (!method_exists($shell, $method))
      throw new UsageException('Unknown command - ' . $command);

    call_user_method_array($method, $shell, $arguments);
  }
}

try {
  LevelDBShell::run(array_slice($argv, 1));
} catch (UsageException $e) {
  if ($msg = $e->getMessage())
    echo $msg . PHP_EOL;
  echo <<< END
Usage:
  ldb -d <folder> <command> [<args>]

The most commonly used commands are:
  keys [pattern]
  set <key> <value>
  get <key>
  delete <key>


END;
}
