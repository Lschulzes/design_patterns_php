<?php

class Originator
{
  public function __construct(private string $state)
  {
    echo "Originator: My initial state is: {$this->state}<br>";
  }

  public function doSomething(): void
  {
    $this->state = $this->generateRandomString(30);
  }

  public function generateRandomString(int $length): string
  {
    return substr(
      str_shuffle(
        str_repeat(
          $x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
          ceil($length / strlen($x))
        )
      ),
      1,
      $length,
    );
  }

  public function save(): Memento
  {
    return new ConcreteMemento($this->state);
  }

  /**
   * Restores the Originator's state from a memento object.
   */
  public function restore(Memento $memento): void
  {
    $this->state = $memento->getState();
    echo "Originator: My state has changed to: {$this->state}<br>";
  }
}

interface Memento
{
  public function getName(): string;
  public function getDate(): string;
  public function getState(): string;
}

class ConcreteMemento implements Memento
{
  public $date;
  public function __construct(public string $state)
  {
    $this->date = date("Y-m-d H:i:s");
  }

  public function getState(): string
  {
    return $this->state;
  }

  public function getName(): string
  {
    return $this->date . " / (" . substr($this->state, 0, 9) . "...)";
  }

  public function getDate(): string
  {
    return $this->date;
  }
}


class Caretaker
{
  private $previousMementos = [];
  private $forwardMementos = [];

  public function __construct(private Originator $originator)
  {
  }

  public function  backup(): void
  {
    echo "<br>Caretaker: Saving Originator's state...<br>";
    $this->previousMementos[] = $this->originator->save();
  }

  public function undo(): void
  {
    $this->movingMementos(false);
  }

  public function forward(): void
  {
    $this->movingMementos(true);
  }

  public function movingMementos(bool $forwards): void
  {
    $popStack = $forwards ? "forwardMementos" : "previousMementos";
    $pushStack = $forwards ? "previousMementos" : "forwardMementos";
    if (!(count($this->{$popStack}))) return;
    $memento = array_pop($this->{$popStack});
    echo "Caretaker: Forwarding state to: " . $memento->getName() . "<br>";
    try {
      $this->{$pushStack}[] = $this->originator->save();
      $this->originator->restore($memento);
    } catch (\Exception $e) {
      $this->movingMementos($popStack === "forwardMementos");
    }
  }

  public function showhistory(): void
  {
    echo "Caretaker: Here's the list of mementos:<br>";
    foreach ($this->previousMementos as $memento) {
      echo $memento->getName() . "<br>";
    }
    foreach ($this->forwardMementos as $memento) {
      echo $memento->getName() . "<br>";
    }
  }
}


$originator = new Originator("Super-duper-super-puper-super.");
$caretaker = new Caretaker($originator);

$caretaker->backup();
$originator->doSomething();

$caretaker->backup();
$originator->doSomething();

$caretaker->backup();
$originator->doSomething();
$caretaker->backup();

echo "<br>";
$caretaker->showHistory();

echo "<br>Client: Now, let's rollback!<br><br>";
$caretaker->undo();

echo "<br>Client: Once more!<br><br>";
$caretaker->undo();
echo "<br>Client: Twonce more!<br><br>";
$caretaker->undo();
echo "<br>Client: Thronce more!<br><br>";
$caretaker->undo();

echo "<br>Client: Going forward!<br><br>";
$caretaker->forward();
echo "<br>Client: Going forward!<br><br>";
$caretaker->forward();
echo "<br>Client: Going forward!<br><br>";
$caretaker->forward();
