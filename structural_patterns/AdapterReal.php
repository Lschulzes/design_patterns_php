<?php

interface Notification
{
  public function send(string $title, string $message);
}

class EmailNotification implements Notification
{
  public function __construct(private string $adminEmail)
  {
  }

  public function send(string $title, string $message)
  {
    mail($this->adminEmail, $title, $message);
    echo "Sent email with title $title to {$this->adminEmail} that says $message";
  }
}

class SlackApi
{
  public function __construct(private string $login, private string $apiKey)
  {
  }

  public function logIn(): void
  {
    echo "Logged in to a slack account '{$this->login}' <br/>";
  }

  public function sendMessage(string $chatId, string $message): void
  {
    echo "Posted following message into the '$chatId' chat: '$message'<br/>";
  }
}


class SlackNotification implements Notification
{
  public function __construct(private SlackApi $slack, private string $chatId)
  {
  }

  public function send(string $title, string $message)
  {
    $slackMessage = "#" . $title . "# " . strip_tags($message);
    $this->slack->logIn();
    $this->slack->sendMessage($this->chatId, $slackMessage);
  }
}

function clientCode(Notification $notification)
{
  // ...

  echo $notification->send(
    "Website is down!",
    "<strong style='color:red;font-size: 50px;'>Alert!</strong> " .
      "Our website is not responding. Call admins and bring it up!"
  );

  // ...
}

echo "Client code is designed correctly and works with email notifications:<br/>";
$notification = new EmailNotification("developers@example.com");
clientCode($notification);
echo "<br/><br/>";


echo "The same client code can work with other classes via adapter:<br/>";
$slackApi = new SlackApi("example.com", "XXXXXXXX");
$notification = new SlackNotification($slackApi, "Example.com Developers");
clientCode($notification);
