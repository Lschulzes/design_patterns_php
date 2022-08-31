<?php

class Context
{
  public function __construct(private State $state)
  {
    $this->transitionTo($state);
  }

  public function transitionTo(State $state): void
  {
    echo "Context: Transition to " . get_class($state) . ".<br>";
    $this->state = $state;
    $this->state->setContext($this);
  }
  public function request1(): void
  {
    $this->state->handle1();
  }

  public function request2(): void
  {
    $this->state->handle2();
  }
}

abstract class State
{
  protected $context;

  public function  setContext(Context $context): void
  {
    $this->context = $context;
  }

  abstract public function handle1(): void;
  abstract public function handle2(): void;
}

class ConcreteStateA extends State
{
  public function handle1(): void
  {
    echo "ConcreteStateA handles request1.<br>";
    echo "ConcreteStateA wants to change the state of the context.<br>";
    $this->context->transitionTo(new ConcreteStateB());
  }

  public function handle2(): void
  {
    echo "ConcreteStateA handles request2.<br>";
  }
}

class ConcreteStateB extends State
{
  public function handle1(): void
  {
    echo "ConcreteStateB handles request1.<br>";
  }

  public function handle2(): void
  {
    echo "ConcreteStateB handles request2.<br>";
    echo "ConcreteStateB wants to change the state of the context.<br>";
    $this->context->transitionTo(new ConcreteStateA());
  }
}
$cA = new ConcreteStateA();
$context = new Context($cA);
$context->request1();
$context->transitionTo($cA);
$context->request2();
$context->request1();
$context->request2();
