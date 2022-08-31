<?php

class Prototype
{
  public $primitive;
  public $component;
  public $circularReference;

  public function __clone()
  {
    $this->component = clone $this->component;

    $this->circularReference = clone $this->circularReference;
    $this->circularReference->prototype = $this;
  }
}


class ComponentWithBackReference
{
  public function __construct(public Prototype $prototype)
  {
  }
}


function clientCode()
{
  $p1 = new Prototype();
  $p1->primitive = 245;
  $p1->component = new DateTime();
  $p1->circularReference = new ComponentWithBackReference($p1);

  $p2 = clone $p1;
  if ($p1->primitive === $p2->primitive) {
    echo "Primitive field values have been carried over to a clone. Yay!<br/>";
  } else {
    echo "Primitive field values have not been copied. Booo!<br/>";
  }
  if ($p1->component === $p2->component) {
    echo "Simple component has not been cloned. Booo!<br/>";
  } else {
    echo "Simple component has been cloned. Yay!<br/>";
  }
  if ($p1->circularReference === $p2->circularReference) {
    echo "Component with back reference has not been cloned. Booo!<br/>";
  } else {
    echo "Component with back reference has been cloned. Yay!<br/>";
  }
  if ($p1->circularReference->prototype === $p2->circularReference->prototype) {
    echo "Component with back reference is linked to original object. Booo!<br/>";
  } else {
    echo "Component with back reference is linked to the clone. Yay!<br/>";
  }
}

clientCode();
