<?php

interface Entity
{
  public function accept(Visitory $visitor): string;
}

interface Visitory
{
  public function visitCompany(Company $company): string;
  public function visitDepartment(Department $department): string;
  public function visitEmployee(Employee $employee): string;
}

class Company implements Entity
{
  public function __construct(public string $name, private array $departments)
  {
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getDepartments(): array
  {
    return $this->departments;
  }


  public function accept(Visitory $visitor): string
  {
    return $visitor->visitCompany($this);
  }
}

class Department implements Entity
{
  public function __construct(public string $name, private array $employees)
  {
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getEmployees(): array
  {
    return $this->employees;
  }

  public function getCost(): float
  {
    return array_reduce($this->employees, fn ($prev, $cur) => $prev + $cur->getSalary(), 0);
  }


  public function accept(Visitory $visitor): string
  {
    return $visitor->visitDepartment($this);
  }
}

class Employee implements Entity
{
  public function __construct(public string $name, private string $position, private int $salary)
  {
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getPosition(): string
  {
    return $this->position;
  }

  public function getSalary(): int
  {
    return $this->salary;
  }


  public function accept(Visitory $visitor): string
  {
    return $visitor->visitEmployee($this);
  }
}

class SalaryReport implements Visitory
{
  public function visitCompany(Company $company): string
  {
    $output = "";
    $total = 0;

    foreach ($company->getDepartments() as $department) {
      $total += $department->getCost();
      $output .= "<br>--" . $this->visitDepartment($department);
    }

    $output = $company->getName() . " (" . money_format("$", $total) . ")<br>" . $output;

    return $output;
  }

  public function visitDepartment(Department $department): string
  {
    return $department->getName() .
      " (" . money_format("$", $department->getCost()) . ")<br><br>" .
      array_reduce($department->getEmployees(), function ($prev, $cur) {
        return $prev .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $this->visitEmployee($cur);
      }, "");
  }

  public function visitEmployee(Employee $employee): string
  {
    return money_format("$", $employee->getSalary()) .
      " " . $employee->getName() .
      " (" . $employee->getPosition() . ")<br>";
  }
}

function money_format(string $flag, float $amount)
{
  return $flag . number_format($amount, 2);
}

$mobileDev = new Department("Mobile Development", [
  new Employee("Albert Falmore", "designer", 100000),
  new Employee("Ali Halabay", "programmer", 100000),
  new Employee("Sarah Konor", "programmer", 90000),
  new Employee("Monica Ronaldino", "QA engineer", 31000),
  new Employee("James Smith", "QA engineer", 30000),
]);
$techSupport = new Department("Tech Support", [
  new Employee("Larry Ulbrecht", "supervisor", 70000),
  new Employee("Elton Pale", "operator", 30000),
  new Employee("Rajeet Kumar", "operator", 30000),
  new Employee("John Burnovsky", "operator", 34000),
  new Employee("Sergey Korolev", "operator", 35000),
]);
$company = new Company("SuperStarDevelopment", [$mobileDev, $techSupport]);

// setlocale(LC_MONETARY, 'en_US');
$report = new SalaryReport();

echo "Client: I can print a report for a whole company:<br><br>";
echo $company->accept($report);

echo "<br>Client: ...or just for a single department:<br><br>";
echo $techSupport->accept($report);
