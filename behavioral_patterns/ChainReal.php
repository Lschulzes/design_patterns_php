<?php

interface Handling
{
  public function check(string $email, string $password): bool;
}

abstract class Middleware implements Handling
{
  private ?Handling $next = null;

  public function linkWith(Middleware $next): Middleware
  {
    $this->next = $next;
    return $this->next;
  }

  public function check(string $email, string $password): bool
  {
    if (!$this->next) return true;
    return $this->next->check($email, $password);
  }
}

class UserExistsMiddleware extends Middleware
{
  public function __construct(private Server $server)
  {
  }

  public function check(string $email, string $password): bool
  {
    if (!$this->server->hasEmail($email)) {
      echo "UserExistsMiddleware: This email is not registered!<br>";
      return false;
    }

    if (!$this->server->isValidPassword($email, $password)) {
      echo "UserExistsMiddleware: Wrong password!<br>";
      return false;
    }

    return parent::check($email, $password);
  }
}

class RoleCheckMiddleware extends Middleware
{
  public function check(string $email, string $password): bool
  {
    if ($email === "admin@example.com") {
      echo "RoleCheckMiddleware: Hello, admin!<br>";
      return true;
    }

    echo "RoleCheckMiddleware: Hello, user!<br>";
    return parent::check($email, $password);
  }
}

class ThrottlingMiddleware extends Middleware
{
  private $request;
  private $currentTime;
  public function __construct(private int $requestPerMinute)
  {
    $this->currentTime = time();
  }

  public function check(string $email, string $password): bool
  {
    if (time() > $this->currentTime + 60) {
      $this->request = 0;
      $this->currentTime = time();
    }

    $this->request++;
    if ($this->request > $this->requestPerMinute) die("ThrottlingMiddleware: Request limit exceeded!<br>");

    return parent::check($email, $password);
  }
}

class  Server
{
  private $users = [];
  private $middleware;
  public function setMiddleware(Middleware $middleware): void
  {
    $this->middleware =  $middleware;
  }

  public function logIn(string $email, string $password): bool
  {
    if ($this->middleware->check($email, $password)) {
      echo "Server: Authorization has been successful!\n";

      return true;
    }
    return false;
  }

  public function register(string $email, string $password)
  {
    if ($this->hasEmail($email)) return;
    $this->users[$email] = $password;
  }

  public function hasEmail(string $email): bool
  {
    return isset($this->users[$email]);
  }

  public function isValidPassword(string $email, string $password): bool
  {
    return $this->users[$email] === $password;
  }
}


$server = new Server();
$server->register("admin@example.com", "admin_pass");
$server->register("user@example.com", "user_pass");

$middleware = new ThrottlingMiddleware(2);
$middleware
  ->linkWith(new UserExistsMiddleware($server))
  ->linkWith(new RoleCheckMiddleware());

$server->setMiddleware($middleware);

do {
  echo "<br>Enter your email:<br>";
  $email = "user@example.com";
  echo "<br>Enter your password:<br>";
  $password = "user_pass";
  $success = $server->logIn($email, $password);
} while (!$success);
