<?php


class Context
{
  public function __construct(private Strategy $strategy)
  {
  }

  public function setStrategy(Strategy $strategy)
  {
    $this->strategy =  $strategy;
  }

  public function doSomeBusinessLogic(): void
  {
    // ...

    echo "Context: Sorting data using the strategy (not sure how it'll do it)<br>";
    $result = $this->strategy->doAlgorithm(["c", "d", "a", "b", "e"]);
    echo implode(",", $result) . "<br>";

    // ...
  }
}

interface Strategy
{
  public function doAlgorithm(array $data): array;
}

class ConcreteStrategyA implements Strategy
{
  public function doAlgorithm(array $data): array
  {
    sort($data);
    return $data;
  }
}
class ConcreteStrategyB implements Strategy
{
  public function doAlgorithm(array $data): array
  {
    rsort($data);

    return $data;
  }
}

$context = new Context(new ConcreteStrategyA());
$context->doSomeBusinessLogic();
$context->setStrategy(new ConcreteStrategyB());
$context->doSomeBusinessLogic();
