<?php

abstract class Component
{
  protected $parent;

  /**
   * Get the value of parent
   */
  public function getParent()
  {
    return $this->parent;
  }

  /**
   * Set the value of parent
   *
   * @return  self
   */
  public function setParent($parent)
  {
    $this->parent = $parent;

    return $this;
  }

  public function add(Component $component): void
  {
  }
  public function remove(Component $component): void
  {
  }
  public function isComposite(): bool
  {
    return false;
  }
  abstract public function operation(): string;
}

class Leaf extends Component
{
  public function operation(): string
  {
    return "Leaf";
  }
}

class Composite extends Component
{
  protected $children;
  public function __construct()
  {
    $this->children = new \SplObjectStorage();
  }

  public function add(Component $component): void
  {
    $this->children->attach($component);
    $component->setParent($this);
  }

  public function remove(Component $component): void
  {
    $this->children->detach($component);
    $component->setParent(null);
  }

  public function isComposite(): bool
  {
    return true;
  }

  public function operation(): string
  {
    $results = [];
    foreach ($this->children as $child) $results[] = $child->operation();
    return "Branch(" . implode("+", $results) . ")";
  }
}


// function execution(Component $component)
// {
//   echo "RESULT: " . $component->operation();
// }

$simple = new Leaf();
echo "Client: I've got a simple component:<br>";
echo "<br><br>";

$tree = new Composite();
$branch1 = new Composite();
$branch1->add(new Leaf());
$branch1->add(new Leaf());
$branch2 = new Composite();
$branch2->add(new Leaf());
$tree->add($branch1);
$tree->add($branch2);
echo "Client: Now I've got a composite tree:<br>";
echo "<br><br>";

function clientCode2(Component $component1, Component $component2)
{
  // ...

  if ($component1->isComposite()) {
    $component1->add($component2);
  }
  echo "RESULT: " . $component1->operation();

  // ...
}

echo "Client: I don't need to check the components classes even when managing the tree:<br>";
clientCode2($tree, $simple);
