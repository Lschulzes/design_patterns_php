<?php

class AlphabeticalOrderIterator  implements \Iterator
{
  private $position = 0;
  public function __construct(private  $collection, private bool $reverse = false)
  {
  }

  public function rewind(): void
  {
    $this->position = $this->reverse ? count($this->collection->getItems()) - 1 : 0;
  }

  public function current()
  {
    return $this->collection->getItems()[$this->position];
  }

  public function next()
  {
    $this->position = $this->position + ($this->reverse ? -1 : 1);
  }

  public function valid()
  {
    return isset($this->collection->getItems()[$this->position]);
  }

  public function key()
  {
    return $this->position;
  }
}

class WordsCollection implements \IteratorAggregate
{
  private $items = [];
  public function getItems()
  {
    return $this->items;
  }
  public function addItem($item)
  {
    $this->items[] = $item;
  }
  public function getIterator(): Iterator
  {
    return new AlphabeticalOrderIterator($this);
  }
  public function getReverseIterator(): Iterator
  {
    return new AlphabeticalOrderIterator($this, true);
  }
}

$collection = new WordsCollection();
$collection->addItem("First");
$collection->addItem("Second");
$collection->addItem("Third");
$collection->addItem("Fourth");

echo "Straight traversal:<br>";
var_dump($collection->getIterator());
foreach ($collection->getIterator() as $item) echo $item . "<br>";
echo "<br>";
echo "Reverse traversal:<br>";
foreach ($collection->getReverseIterator() as $item) echo $item . "<br>";
