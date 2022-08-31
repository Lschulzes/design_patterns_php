<?php

class Abstraction
{
  public function __construct(protected Implementation $implementation)
  {
  }

  public function operation(): string
  {
    return "Abstraction: Base operation with: <br/>" . $this->implementation->operationImplementaion();
  }
}

class ExtendedAbstraction extends Abstraction
{
  public function operation(): string
  {
    $return = "ExtendedAbstraction: This extended operation doubles everything <br/>";
    $return .= $this->implementation->operationImplementaion();
    $return .= $this->implementation->operationImplementaion();
    return $return;
  }
}

interface Implementation
{
  public function operationImplementaion(): string;
}

class ConcreteImplementationA implements Implementation
{
  public function operationImplementaion(): string
  {
    return "ConcreteImplementationA: Here's the result on the platform A.<br/>";
  }
}

class ConcreteImplementationB implements Implementation
{
  public function operationImplementaion(): string
  {
    return "ConcreteImplementationB: Here's the result on the platform B.<br/>";
  }
}

function clientCode(Abstraction $abstraction)
{
  echo $abstraction->operation();
}

$implementation = new ConcreteImplementationA();
$abstraction = new Abstraction($implementation);
clientCode($abstraction);

$implementationB = new ConcreteImplementationB();
$abstractionB = new Abstraction($implementationB);
clientCode($abstractionB);

$implementationA2 = new ConcreteImplementationA();
$abstractionA2 = new ExtendedAbstraction($implementationA2);
clientCode($abstractionA2);
