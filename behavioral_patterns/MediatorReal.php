<?php

class EventDispatcher
{
  private $observers = [];

  public function __construct()
  {
    $this->observers['*'] = [];
  }

  private function initEventGroup(string &$event = "*"): void
  {
    if (!isset($this->observers[$event])) {
      $this->observers[$event] = [];
    }
  }

  private function getEventObservers(string $event = "*"): array
  {
    $this->initEventGroup($event);
    $group = $this->observers[$event];
    $all = $this->observers["*"];
    return array_merge($group, $all);
  }

  public function attach(Observer $observer, string $event = "*"): void
  {
    $this->initEventGroup($event);
    $this->observers[$event][] = $observer;
  }

  public function detach(Observer $observer, string $event = "*"): void
  {
    foreach ($this->getEventObservers($event) as $key => $s) {
      if ($s === $observer) unset($this->observers[$event][$key]);
    }
  }

  public function trigger(string $event, object $emitter, $data = null): void
  {
    echo "EventDispatcher: Broadcasting the '$event' event.<br>";
    foreach ($this->getEventObservers($event) as $observer) {
      $observer->update($event, $emitter, $data);
    }
  }
}

function events(): EventDispatcher
{
  static $eventDispatcher;
  if (!$eventDispatcher) $eventDispatcher = new EventDispatcher();
  return $eventDispatcher;
}


interface Observer
{
  public function update(string $event, object $emitter, $data = null): void;
}


class UserRepository implements Observer
{
  private $users = [];

  public function __construct()
  {
    events()->attach($this, "users:deleted");
  }

  public function update(string $event, object $emitter, $data = null): void
  {
    switch ($event) {
      case 'users:deleted':
        if ($emitter === $this) return;
        $this->deleteUser($data, true);
        break;
    }
  }

  public function initialize(string $filename): void
  {
    echo "UserRepository: Loading user records from a file.<br>";
    events()->trigger("users:init", $this, $filename);
  }

  public function createUser(array $data, bool $silent = false): User
  {
    $user = new User();
    $user->update($data);

    $id = bin2hex(openssl_random_pseudo_bytes(16));
    $user->update(["id" => $id]);
    $this->users[$id] = $user;

    if (!$silent) events()->trigger("users:created", $this, $user);

    return $user;
  }

  public function updateUser(User $user, array $data, bool $silent = false): ?User
  {
    echo "UserRepository: Updating a user.<br>";

    $id = $user->attributes["id"];
    if (!isset($this->users[$id])) return null;

    $user = $this->users[$id];
    $user->update($data);

    if (!$silent) events()->trigger("users:updated", $this, $user);


    return $user;
  }

  public function deleteUser(User $user, bool $silent = false): void
  {
    echo "UserRepository: Deleting a user.<br>";

    $id = $user->attributes["id"];
    if (!isset($this->users[$id])) return;


    unset($this->users[$id]);

    if (!$silent) events()->trigger("users:deleted", $this, $user);
  }
}


class User
{
  public $attributes = [];

  public function update($data): void
  {
    $this->attributes = array_merge($this->attributes, $data);
  }

  public function delete(): void
  {
    echo "User: I can now delete myself without worrying about the repository.<br>";
    events()->trigger("users:deleted", $this, $this);
  }
}

class Logger implements Observer
{
  public function __construct(private string $filename)
  {
    if (file_exists($this->filename)) unlink($this->filename);
  }

  public function  update(string $event, object $emitter, $data = null): void
  {
    $entry = date("Y-m-d H:i:s") . ": '$event' with data '" . json_encode($data) . "'<br>";
    file_put_contents($this->filename, $entry, FILE_APPEND);

    echo "Logger: I've written '$event' entry to the log.<br>";
  }
}

class OnboardingNotification implements Observer
{
  public function __construct(private string $adminEmail)
  {
  }

  public function update(string $event, object $emitter, $data = null): void
  {
    // mail($this->adminEmail,
    //     "Onboarding required",
    //     "We have a new user. Here's his info: " .json_encode($data));
    echo "OnboardingNotification: The notification has been emailed!<br>";
  }
}


$repository = new UserRepository();
events()->attach($repository, "facebook:update");

$logger = new Logger(__DIR__ . "/log.txt");
events()->attach($logger, "*");

$onboarding = new OnboardingNotification("1@example.com");
events()->attach($onboarding, "users:created");

$repository->initialize(__DIR__ . "users.csv");

$user = $repository->createUser([
  "name" => "John Smith",
  "email" => "john99@example.com"
]);

$user->delete();
