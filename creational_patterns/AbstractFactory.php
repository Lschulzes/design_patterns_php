<?php


interface AbstractFactory
{
  public function createProductA(): AbstractProductA;
  public function createProductB(): AbstractProductB;
}

class ConcreteFactory1 implements AbstractFactory
{
  public function createProductA(): AbstractProductA
  {
    return new ConcreteProductA1();
  }
  public function createProductB(): AbstractProductB
  {
    return new ConcreteProductB1();
  }
}

class ConcreteFactory2 implements AbstractFactory
{
  public function createProductA(): AbstractProductA
  {
    return new ConcreteProductA2();
  }
  public function createProductB(): AbstractProductB
  {
    return new ConcreteProductB2();
  }
}

interface AbstractProductA
{
  public function usefulFunctionA(): string;
}

interface AbstractProductB
{
  public function usefulFunctionB(): string;
  public function anotherUsefulFunctionB(): string;
}

class ConcreteProductA1 implements AbstractProductA
{
  public function usefulFunctionA(): string
  {
    return "Product A1";
  }
}
class ConcreteProductA2 implements AbstractProductA
{
  public function usefulFunctionA(): string
  {
    return "Product A2";
  }
}
class ConcreteProductB1 implements AbstractProductB
{
  public function usefulFunctionB(): string
  {
    return "Product B1";
  }

  public function anotherUsefulFunctionB(): string
  {
    return "Product good";
  }
}
class ConcreteProductB2 implements AbstractProductB
{
  public function usefulFunctionB(): string
  {
    return "Product B2";
  }

  public function anotherUsefulFunctionB(): string
  {
    return "Product bad";
  }
}

function clientCode(AbstractFactory $factory)
{
  $productA = $factory->createProductA();
  $productB = $factory->createProductB();

  echo $productA->usefulFunctionA() . $productB->usefulFunctionB() . $productB->anotherUsefulFunctionB();
}

// clientCode(new ConcreteFactory1());
clientCode(new ConcreteFactory2());
