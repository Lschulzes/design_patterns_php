<?php

interface Component
{
  public function operation(): string;
}

class ConcreteComponent implements Component
{
  public function operation(): string
  {
    return "ConcreteComponent";
  }
}

class Decorator implements Component
{
  public function __construct(protected Component $component)
  {
  }

  public function operation(): string
  {
    return $this->component->operation();
  }
}

class RevertsA extends Decorator
{
  public function operation(): string
  {
    return  strrev(parent::operation());
  }
}
class UppersB extends Decorator
{
  public function operation(): string
  {
    return strtoupper(parent::operation());
  }
}

function execute(Component $component)
{
  echo "Result: " . $component->operation();
}

$simple = new ConcreteComponent();
echo "Client: I've got a simple component:<br>";
execute($simple);
echo "<br><br>";

/**
 * ...as well as decorated ones.
 *
 * Note how decorators can wrap not only simple components but the other
 * decorators as well.
 */
echo "<br><br><br>Client: I've got the component decorated with Revert:<br>";
$decoratorRev = new RevertsA($simple);
execute($decoratorRev);
echo "<br><br><br>Client: I've got the component decorated with Upper:<br>";
$decoratorUpp = new UppersB($simple);
execute($decoratorUpp);
echo "<br><br><br>Client: I've got the component decorated with Upper And Revert:<br>";
$decoratorUpp = new UppersB($decoratorRev);
execute($decoratorUpp);
