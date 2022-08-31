<?php

interface Subject
{
  public function request(): void;
}

class RealSubject implements Subject
{
  public function request(): void
  {
    echo "RealSubject: Handling Request <br>";
  }
}

class Proxy implements Subject
{
  public function __construct(private RealSubject $realSubject)
  {
  }

  public function request(): void
  {
    if ($this->checkAccess()) {
      $this->realSubject->request();
      $this->logAccess();
    } else {
      echo "Access not valid, ending running processes";
    }
  }

  private function checkAccess(): bool
  {
    echo "Proxy: Checking access prior to firing a real request.<br>";
    return rand(0, 9) > 4 ? true : false;
  }

  private function logAccess(): void
  {
    echo "Proxy: Logging the time of request.<br>";
  }
}

function proxy(Subject $subject)
{
  $subject->request();
}

echo "Client: Executing the client code with a real subject:<br>";
$realSubject = new RealSubject();
proxy($realSubject);

echo "<br>";


echo "Client: Executing the same client code with a proxy:<br>";

$proxy = new Proxy($realSubject);
proxy($proxy);
