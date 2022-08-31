<?php

class Facade
{
  public function __construct(protected Subsystem1 $subsystem1, protected Subsystem2 $subsystem2)
  {
  }

  public function operation(): string
  {
    $result = "Facade initializes subsystems:<br>";
    $result .= $this->subsystem1->operation1();
    $result .= $this->subsystem2->operation1();
    $result .= "Facade orders subsystems to perform the action:<br>";
    $result .= $this->subsystem1->operationN();
    $result .= $this->subsystem2->operationZ();
    return $result;
  }
}

class Subsystem1
{
  public function operation1(): string
  {
    return "Subsystem1: Ready!<br>";
  }
  public function operationN(): string
  {
    return "Subsystem1: Go!<br>";
  }
}
class Subsystem2
{
  public function operation1(): string
  {
    return "Subsystem2: Get Ready!<br>";
  }
  public function operationZ(): string
  {
    return "Subsystem2: Fire!<br>";
  }
}

function execute(Facade $facade)
{
  echo $facade->operation();
}

$subsystem1 = new Subsystem1();
$subsystem2 = new Subsystem2();
$facade = new Facade($subsystem1, $subsystem2);

execute($facade);
