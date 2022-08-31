<?php


class Target
{
  public function request(): string
  {
    return "Target: default behavior";
  }
}

class Adaptee
{
  public function specificRequest(): string
  {
    return ".eetpadA eht fo roivaheb laicepS";
  }
}

class Adapter extends Target
{
  public function __construct(private Adaptee $adaptee)
  {
  }

  public function request(): string
  {
    return "Adapter: (TRANSLATED) " . strrev($this->adaptee->specificRequest());
  }
}

function clientCode(Target $target)
{
  echo $target->request();
}

echo "Client: I can work just fine with the Target objects:<br/>";
$target = new Target();
clientCode($target);
echo "<br/><br/>";

$adaptee = new Adaptee();
echo "Client: The Adaptee class has a weird interface. See, I don't understand it:<br/>";
echo "Adaptee: " . $adaptee->specificRequest();
echo "<br/><br/>";

echo "Client: But I can work with it via the Adapter:<br/>";
$adapter = new Adapter($adaptee);
clientCode($adapter);
