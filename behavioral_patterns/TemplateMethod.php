<?php

abstract class AbstractClass
{
  final public function templateMethod(): void
  {
    $this->baseOperation1();
    $this->requiredOperation1();
    $this->baseOperation2();
    $this->hook1();
    $this->requiredOperation2();
    $this->baseOperation3();
    $this->hook2();
  }
  /**
   * These operations already have implementations.
   */
  protected function baseOperation1(): void
  {
    echo "AbstractClass says: I am doing the bulk of the work<br>";
  }

  protected function baseOperation2(): void
  {
    echo "AbstractClass says: But I let subclasses override some operations<br>";
  }

  protected function baseOperation3(): void
  {
    echo "AbstractClass says: But I am doing the bulk of the work anyway<br>";
  }

  /**
   * These operations have to be implemented in subclasses.
   */
  abstract protected function requiredOperation1(): void;

  abstract protected function requiredOperation2(): void;

  /**
   * These are "hooks." Subclasses may override them, but it's not mandatory
   * since the hooks already have default (but empty) implementation. Hooks
   * provide additional extension points in some crucial places of the
   * algorithm.
   */
  protected function hook1(): void
  {
  }

  protected function hook2(): void
  {
  }
}


class ConcreteClass1 extends AbstractClass
{
  protected function requiredOperation1(): void
  {
    echo "ConcreteClass1 says: Implemented Operation1<br>";
  }

  protected function requiredOperation2(): void
  {
    echo "ConcreteClass1 says: Implemented Operation2<br>";
  }
}

class ConcreteClass2 extends AbstractClass
{
  protected function requiredOperation1(): void
  {
    echo "ConcreteClass2 says: Implemented Operation1<br>";
  }

  protected function requiredOperation2(): void
  {
    echo "ConcreteClass2 says: Implemented Operation2<br>";
  }

  protected function hook1(): void
  {
    echo "ConcreteClass2 says: Overridden Hook1<br>";
  }
}

function run(AbstractClass $abstractClass)
{
  $abstractClass->templateMethod();
}

echo "Same client code can work with different subclasses:<br>";
run(new ConcreteClass1());
echo "<br>";

echo "Same client code can work with different subclasses:<br>";
run(new ConcreteClass2());
