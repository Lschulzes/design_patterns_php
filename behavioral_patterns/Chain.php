<?php
return;
interface Handler
{
  public function setNext(Handler $handler): Handler;

  public function handle(string $request): ?string;
}

abstract class AbstractHandler implements Handler
{
  private ?Handler $nextHandler = null;

  public function setNext(Handler $handler): Handler
  {
    $this->nextHandler = $handler;
    return $this->nextHandler;
  }

  public function handle(string $request): ?string
  {
    if ($this?->nextHandler) {
      return $this->nextHandler->handle($request);
    }
    return null;
  }
}

class MonkeyHandler extends AbstractHandler
{
  public function handle(string $request): ?string
  {
    if ($request === "Banana") return "Monkey: I'll eat the " . $request . ".<br>";
    return parent::handle($request);
  }
}

class SquirrelHandler extends AbstractHandler
{
  public function handle(string $request): ?string
  {
    if ($request === "Nut") return "Squirrel: I'll eat the " . $request . ".<br>";
    return parent::handle($request);
  }
}

class DogHandler extends AbstractHandler
{
  public function handle(string $request): ?string
  {
    if ($request === "MeatBall") return "Dog: I'll eat the " . $request . ".<br>";
    return parent::handle($request);
  }
}



function chain(Handler $handler)
{
  foreach (["Nut", "Banana", "MeatBall", "Glass"] as $food) {
    echo "Client: Who wants a " . $food . "?<br>";
    $result = $handler->handle($food);
    if ($result) {
      echo "  " . $result;
    } else {
      echo "  " . $food . " was left untouched.<br>";
    }
  }
}

$monkey = new MonkeyHandler();
$squirrel = new SquirrelHandler();
$dog = new DogHandler();

$monkey->setNext($squirrel)->setNext($dog);

chain($monkey);
