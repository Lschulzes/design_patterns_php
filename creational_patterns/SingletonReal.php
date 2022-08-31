<?php

class Singleton
{
  private static $instances = [];
  protected function __construct()
  {
  }
  protected function __clone()
  {
  }
  public function __wakeup()
  {
  }

  public static function getInstance()
  {
    $subclass = static::class;
    if (!isset(self::$instances[$subclass])) {
      self::$instances[$subclass] = new static();
    }

    return self::$instances[$subclass];
  }
}

final class Logger extends Singleton
{
  private $fileHandle;
  protected function __construct()
  {
    $this->fileHandle = fopen('test.txt', 'wb');
  }

  public function writeLog(string $message): void
  {
    $date = date('Y-m-d');
    fwrite($this->fileHandle, "$date: $message <br/>");
  }

  public static function log(string $message): void
  {
    $logger = static::getInstance();
    $logger->writeLog($message);
  }
}

final class Config extends Singleton
{
  private $hashmap = [];
  public function getValue(string $key): string
  {
    return $this->hashmap[$key];
  }
  public function setValue(string $key, string $value): void
  {
    $this->hashmap[$key] = $value;
  }
}

(function () {
  Logger::log("Started!");
  $l1 = Logger::getInstance();
  $l2 = Logger::getInstance();
  if ($l1 === $l2) {
    Logger::log("Logger has a single instance.");
  } else {
    Logger::log("Loggers are different.");
  }

  $config1 = Config::getInstance();
  $login = "test_login";
  $password = "test_password";
  $config1->setValue("login", $login);
  $config1->setValue("password", $password);
  $config2 = Config::getInstance();
  if (
    $login == $config2->getValue("login") &&
    $password == $config2->getValue("password")
  ) {
    Logger::log("Config singleton also works fine.");
  }

  Logger::log("Finished!");
})();
