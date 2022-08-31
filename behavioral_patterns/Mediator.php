<?php

interface Mediator
{
  public function notify(object $sender, string $event): void;
}

class ConcreteMediator implements Mediator
{
  public function __construct(public Component1 $component1, public Component2 $component2)
  {
    $this->component1->setMediator($this);
    $this->component2->setMediator($this);
  }

  public function  notify(object $sender, string $event): void
  {
    if ($event === "A") {
      echo "Mediator reacts on A and triggers following operations:<br>";
      $this->component2->doC();
    }
    if ($event === "D") {
      echo "Mediator reacts on D and triggers following operations:<br>";
      $this->component1->doB();
      $this->component2->doC();
    }
    if ($event === "BRR") {
      echo "Mediator reacts on BRR and triggers following operations:<br>";
      if (!($this->component1 instanceof $sender)) $this->component1->doBRR(true);
      if (!($this->component2 instanceof $sender)) $this->component2->doBRR(true);
    }
  }
}

class BaseComponent
{
  public function __construct(protected ?Mediator $mediator = null)
  {
  }

  public function  setMediator(Mediator $mediator): void
  {
    $this->mediator = $mediator;
  }
}


class Component1 extends BaseComponent
{
  public function doA(): void
  {
    echo "Component 1 does A.<br>";
    $this->mediator->notify($this, "A");
  }
  public function doB(): void
  {
    echo "Component 1 does B.<br>";
    $this->mediator->notify($this, "B");
  }
  public function doBRR($notify = false): void
  {
    echo "Component 1 does BRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR.<br>";
    !$notify && $this->mediator->notify($this, "BRR");
  }
}

class Component2 extends BaseComponent
{
  public function doC(): void
  {
    echo "Component 2 does C.<br>";
    $this->mediator->notify($this, "C");
  }
  public function doD(): void
  {
    echo "Component 2 does D.<br>";
    $this->mediator->notify($this, "D");
  }
  public function doBRR($notify = false): void
  {
    echo "Component 2 does BRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR.<br>";
    !$notify && $this->mediator->notify($this, "BRR");
  }
}

$c1 = new Component1();
$c2 = new Component2();
$mediator = new ConcreteMediator($c1, $c2);
// echo "Client triggers operation A.\n";
$c1->doBRR();

// echo "\n";
// echo "Client triggers operation D.\n";
// $c2->doD();
