<?php

interface Componento
{
  public function accept(Visitor $visitor): void;
}

interface Visitor
{
  public function visitConcreteComponentA(ConcreteComponentA $element): void;
  public function visitConcreteComponentB(ConcreteComponentB $element): void;
}


class ConcreteComponentA implements Componento
{
  public function accept(Visitor $visitor): void
  {
    $visitor->visitConcreteComponentA($this);
  }

  public function exclusiveMethodOfConcreteComponentA(): string
  {
    return "A";
  }
}

class ConcreteComponentB implements Componento
{
  public function accept(Visitor $visitor): void
  {
    $visitor->visitConcreteComponentB($this);
  }

  public function exclusiveMethodOfConcreteComponentB(): string
  {
    return "B";
  }
}


class ConcreteVisitor1 implements Visitor
{
  public function visitConcreteComponentA(ConcreteComponentA $element): void
  {
    echo $element->exclusiveMethodOfConcreteComponentA() . "ConcreteVisitor1<br>";
  }

  public function visitConcreteComponentB(ConcreteComponentB $element): void
  {
    echo $element->exclusiveMethodOfConcreteComponentB() . "ConcreteVisitor1<br>";
  }
}


class ConcreteVisitor2 implements Visitor
{
  public function visitConcreteComponentA(ConcreteComponentA $element): void
  {
    echo $element->exclusiveMethodOfConcreteComponentA() . "ConcreteVisitor2<br>";
  }

  public function visitConcreteComponentB(ConcreteComponentB $element): void
  {
    echo $element->exclusiveMethodOfConcreteComponentB() . "ConcreteVisitor2<br>";
  }
}

function run(array $components, Visitor $visitor)
{
  foreach ($components as $component) {
    $component->accept($visitor);
  }
}

$components = [
  new ConcreteComponentA(),
  new ConcreteComponentB(),
];

$visitor1 = new ConcreteVisitor1();
run($components, $visitor1);
echo "<br><br><br><br>";
$visitor2 = new ConcreteVisitor2();
run($components, $visitor2);
