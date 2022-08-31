<?php



interface Product
{
  public function operation(): string;
}

abstract class Creator
{
  abstract public function factoryMethod(): Product;

  public function someOperation(): string
  {
    $product = $this->factoryMethod();
    $result = $product->operation();
    return $result;
  }
}

class EmployeesListCreator extends Creator
{
  public function __construct(private array $employees_list)
  {
  }
  public function factoryMethod(): Product
  {
    return new EmployeesList($this->employees_list);
  }
}

class EmployeesList implements Product
{
  public function __construct(private array $employees_list)
  {
  }
  public function operation(): string
  {
    $employees = "";
    foreach ($this->employees_list as $employee) $employees .= "<br/>" . $employee;
    return $employees;
  }
}

class ConcreteCreator1 extends Creator
{
  public function factoryMethod(): Product
  {
    return new ConcreteProduct1();
  }
}

class ConcreteCreator2 extends Creator
{
  public function factoryMethod(): Product
  {
    return new ConcreteProduct2();
  }
}

class ConcreteProduct1 implements Product
{
  public function operation(): string
  {
    return "Product One";
  }
}

class ConcreteProduct2 implements Product
{
  public function operation(): string
  {
    return "Product Two";
  }
}


function clientCode(Creator $creator)
{
  echo "Client: I'm not aware of the creator's class, but it still works.\n" . $creator->someOperation();
}


clientCode(new EmployeesListCreator(['Lucas', 'Hanna', "Abby", "Rachel", 'Isabel']));
