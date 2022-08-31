<?php

abstract class SocialNetwork
{
  public function __construct(public string $username, public string $password)
  {
  }

  /**
   * These operations already have implementations.
   */
  public  function post(string $message): bool
  {
    // Authenticate before posting. Every network uses a different
    // authentication method.
    if ($this->logIn($this->username, $this->password)) {
      // Send the post data. All networks have different APIs.
      $result = $this->sendData($message);
      if (method_exists($this, "afterMessage")) $this->afterMessage();
      // ...
      $this->logOut();

      return $result;
    }

    return false;
  }

  abstract public function logIn(string $userName, string $password): bool;

  abstract public function sendData(string $message): bool;

  abstract public function logOut(): void;

  protected function afterMessage(): void
  {
  }
}

class Facebook extends SocialNetwork
{
  public function logIn(string $userName, string $password): bool
  {
    echo "<br>Checking user's credentials...<br>";
    echo "Name: " . $this->username . "<br>";
    echo "Password: " . str_repeat("*", strlen($this->password)) . "<br>";

    simulateNetworkLatency();

    echo "<br><br>Facebook: '" . $this->username . "' has logged in successfully.<br>";

    return true;
  }

  public function sendData(string $message): bool
  {
    echo "Facebook: '" . $this->username . "' has posted '" . $message . "'.<br>";

    return true;
  }

  public function logOut(): void
  {
    echo "Facebook: '" . $this->username . "' has been logged out.<br>";
  }
}

class Twitter extends SocialNetwork
{
  public function logIn(string $userName, string $password): bool
  {
    echo "<br>Checking user's credentials...<br>";
    echo "Name: " . $this->username . "<br>";
    echo "Password: " . str_repeat("*", strlen($this->password)) . "<br>";

    simulateNetworkLatency();

    echo "<br><br>Twitter: '" . $this->username . "' has logged in successfully.<br>";

    return true;
  }

  public function sendData(string $message): bool
  {
    echo "Twitter: '" . $this->username . "' has posted '" . $message . "'.<br>";

    return true;
  }

  public function logOut(): void
  {
    echo "Twitter: '" . $this->username . "' has been logged out.<br>";
  }
}

function simulateNetworkLatency()
{
  for ($i = 0; $i < 5; $i++) {
    echo ".";
    sleep(1);
    $i++;
  }
}

echo "Username: <br>";
$username = "lschulzes";
echo "Password: <br>";
$password = "123";
echo "Message: <br>";
$message = "Cya!";

echo "<br>Choose the social network to post the message:<br>" .
  "1 - Facebook<br>" .
  "2 - Twitter<br>";
$choice = 2;

// Now, let's create a proper social network object and send the message.
if ($choice == 1) {
  $network = new Facebook($username, $password);
} elseif ($choice == 2) {
  $network = new Twitter($username, $password);
} else {
  die("Sorry, I'm not sure what you mean by that.<br>");
}
$network->post($message);
