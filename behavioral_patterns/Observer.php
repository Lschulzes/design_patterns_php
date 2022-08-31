<?php

class Subject implements \SplSubject
{
  public $state;
  private $observers;

  public function __construct()
  {
    $this->observers = new \SplObjectStorage();
  }

  public function attach(SplObserver $observer): self
  {
    echo "Subject: Attached an observer.<br>";
    $this->observers->attach($observer);
    return $this;
  }
  public function detach(SplObserver $observer): void
  {
    $this->observers->detach($observer);
    echo "Subject: Detached an observer.<br>";
  }
  public function notify(): void
  {
    echo "Subject: Notifying observers...<br>";
    foreach ($this->observers as $observer) $observer->update($this);
  }

  public function someBusinessLogic(): void
  {
    echo "<br>Subject: I'm doing something important.<br>";
    $this->state = rand(0, 10);

    echo "Subject: My state has just changed to: {$this->state}<br>";
    $this->notify();
  }
}


class ConcreteObserverB implements \SplObserver
{
  public function update(SplSubject $subject): void
  {
    if ($subject->state < 3) echo "ConcreteObserverA: Reacted to the event.<br>";
  }
}
class ConcreteObserverA implements \SplObserver
{
  public function update(SplSubject $subject): void
  {
    if ($subject->state === 0 || $subject->state > 3) echo "ConcreteObserverB: Reacted to the event.<br>";
  }
}


$subject = new Subject();

$o1 = new ConcreteObserverA();
$o2 = new ConcreteObserverB();

$subject->attach($o1)->attach($o2);

$subject->someBusinessLogic();
$subject->someBusinessLogic();
$subject->someBusinessLogic();
$subject->someBusinessLogic();

$subject->detach($o2);

$subject->someBusinessLogic();
$subject->someBusinessLogic();
$subject->someBusinessLogic();
$subject->someBusinessLogic();
