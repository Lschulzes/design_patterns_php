<?php

interface Builder
{
  public function producePartA(): void;
  public function producePartB(): void;
  public function producePartC(): void;
}

class ConcreteBuilder1 implements Builder
{
  private $product;

  public function __construct()
  {
    $this->reset();
  }

  public function reset(): void
  {
    $this->product = new Product1();
  }

  public function producePartA(): void
  {
    $this->product->parts[] = "PartA1";
  }
  public function producePartB(): void
  {
    $this->product->parts[] = "PartB1";
  }
  public function producePartC(): void
  {
    $this->product->parts[] = "PartC1";
  }
  public function getProduct(): Product1
  {
    $result = $this->product;
    $this->reset();
    return $result;
  }
}

class Product1
{
  public $parts = [];

  public function listParts(): void
  {
    echo "Product parts:" . implode(", ", $this->parts);
  }
}

class Director
{
  private $builder;

  public function setBuilder(Builder $builder): void
  {
    $this->builder = $builder;
  }

  public function buildMVP(): void
  {
    $this->builder->producePartA();
  }

  public function buildFullFeaturedProduct(): void
  {
    $this->builder->producePartA();
    $this->builder->producePartB();
    $this->builder->producePartC();
  }
}

function clientCode(Director $director)
{
  $builder = new ConcreteBuilder1();
  $director->setBuilder($builder);
  echo "MVP\n<br/>";
  $director->buildMVP();
  $builder->getProduct()->listParts();

  echo "<br/>\nFull Fledge\n<br/>";
  $director->buildFullFeaturedProduct();
  $builder->getProduct()->listParts();
  echo "<br/>\nCustom Product\n<br/>";
  $builder->producePartA();
  $builder->producePartC();
  $builder->getProduct()->listParts();
}

clientCode(new Director());
