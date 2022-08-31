<?php

abstract class FormElement
{
  protected $data;

  public function __construct(protected string $name, protected string $title)
  {
  }

  abstract public function render(): string;

  /**
   * Get the value of data
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * Set the value of data
   *
   * @return  self
   */
  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */
  public function getName()
  {
    return $this->name;
  }
}

class Input extends FormElement
{
  public function __construct(string $name, string $title, private string $type)
  {
    parent::__construct($name, $title);
  }

  public function render(): string
  {
    return "<label for=\"{$this->name}\">{$this->title}</label><br>" .
      "<input name=\"{$this->name}\" type=\"{$this->type}\" value=\"{$this->data}\"><br>";
  }
}

abstract class FieldComposite extends FormElement
{
  protected $fields = [];

  public function add(FormElement $field): void
  {
    $name = $field->getName();
    $this->fields[$name] = $field;
  }

  public function remove(FormElement $component): void
  {
    $this->fields = array_filter($this->fields, function ($child) use ($component) {
      return $child != $component;
    });
  }

  public function setData($data): void
  {
    foreach ($this->fields as $name => $field) {
      if (isset($data[$name])) $field->setData($data[$name]);
    }
  }

  public function getData(): array
  {
    $data = [];
    foreach ($this->fields as $name => $field) $data[$name] = $field->getData();
    return $data;
  }

  public function render(): string
  {
    $output = "";

    foreach ($this->fields as $name => $field) $output .= $field->render();
    return $output;
  }
}

class Fieldset extends FieldComposite
{
  public function render(): string
  {
    $output = parent::render();
    return "<fieldset><legend>{$this->title}</legend><br>$output</fieldset><br>";
  }
}

class Form extends FieldComposite
{
  public function __construct(string $name, string $title, protected string $url)
  {
    parent::__construct($name, $title);
  }

  public function render(): string
  {
    $output = parent::render();
    return "<form action=\"{$this->url}\"><br><h3>{$this->title}</h3><br>$output</form><br>";
  }
}


function getProductForm(): FormElement
{
  $form = new Form('product', "Add product", "/product/add");
  $form->add(new Input('name', "Name", 'text'));
  $form->add(new Input('description', "Description", 'text'));

  $picture = new Fieldset('photo', "Product photo");
  $picture->add(new Input('caption', "Caption", 'text'));
  $picture->add(new Input('image', "Image", 'file'));

  $form->add($picture);

  return $form;
}

function loadProductData(FormElement $form)
{
  $data = [
    'name' => 'Apple MacBook',
    'description' => 'A decent laptop.',
    'photo' => [
      'caption' => 'Front photo.',
      'image' => 'photo1.png',
    ],
  ];

  $form->setData($data);
}


function renderProduct(FormElement $form)
{
  // ..

  echo $form->render();

  // ..
}

$form = getProductForm();
loadProductData($form);
renderProduct($form);
