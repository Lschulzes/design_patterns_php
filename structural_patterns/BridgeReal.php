<?php

abstract class Page
{
  public function __construct(protected Renderer $renderer)
  {
  }

  public function changeRender(Renderer $renderer): void
  {
    $this->renderer = $renderer;
  }

  abstract public function view(): string;
}


class SimplePage extends Page
{
  public function __construct(Renderer $renderer, protected string $title, protected string $content)
  {
    parent::__construct($renderer);
  }

  public function view(): string
  {
    return $this->renderer->renderParts([
      $this->renderer->renderHeader(),
      $this->renderer->renderTitle($this->title),
      $this->renderer->renderTextBlock($this->content),
      $this->renderer->renderFooter()
    ]);
  }
}

class ProductPage extends Page
{
  public function __construct(Renderer $renderer, protected Product $product)
  {
    parent::__construct($renderer);
  }

  public function view(): string
  {
    return $this->renderer->renderParts([
      $this->renderer->renderHeader(),
      $this->renderer->renderTitle($this->product->getTitle()),
      $this->renderer->renderTextBlock($this->product->getDescription()),
      $this->renderer->renderImage($this->product->getImage()),
      $this->renderer->renderLink("/cart/add" . $this->product->getId(), "Add To Cart"),
      $this->renderer->renderFooter(),
    ]);
  }
}

interface Renderer
{
  public function renderTitle(string $title): string;
  public function renderTextBlock(string $text): string;
  public function renderImage(string $url): string;
  public function renderLink(string $url, string $title): string;
  public function renderHeader(): string;
  public function renderFooter(): string;
  public function renderParts(array $parts): string;
}

class Product
{
  public function __construct(private string $id, private string $title, private string $description, private string $image, private float $price)
  {
  }

  public function getId(): string
  {
    return $this->id;
  }
  public function getTitle(): string
  {
    return $this->title;
  }
  public function getDescription(): string
  {
    return $this->description;
  }
  public function getImage(): string
  {
    return $this->image;
  }
  public function getPrice(): string
  {
    return $this->price;
  }
}

class HTMLRenderer implements Renderer
{
  public function renderTitle(string $title): string
  {
    return "<h1>$title</h1>";
  }
  public function renderImage(string $url): string
  {
    return "<img src='$url'/>";
  }
  public function renderTextBlock(string $text): string
  {
    return "<div class='text'>$text</div>";
  }
  public function renderLink(string $url, string $title): string
  {
    return "<a href='$url'>$title</a>";
  }
  public function renderHeader(): string
  {
    return "<html><body>";
  }
  public function renderFooter(): string
  {
    return "</body></html>";
  }
  public function renderParts(array $parts): string
  {
    return implode("\n", $parts);
  }
}

class JSONRenderer implements Renderer
{
  public function renderTitle(string $title): string
  {
    return '"title": "' . $title . '"';
  }
  public function renderImage(string $url): string
  {
    return '"url": "' . $url . '"';
  }
  public function renderTextBlock(string $text): string
  {
    return '"text": "' . $text . '"';
  }
  public function renderLink(string $url, string $title): string
  {
    return '"link": {"href": ' . $url . '", "title": ' . $title . '"}';
  }
  public function renderHeader(): string
  {
    return '';
  }
  public function renderFooter(): string
  {
    return '';
  }
  public function renderParts(array $parts): string
  {
    return "{\n" . implode(",\n", array_filter($parts)) . "\n}";
  }
}

function testingCode(Page $page)
{
  echo $page->view();
}


$HTMLRenderer = new HTMLRenderer();
$JSONRenderer = new JSONRenderer();
$product = new Product('150_oex_kb', "oex Mechanic Keyboard", "The most mechanical artifact of your house", "https://m.media-amazon.com/images/I/71NHCdOGs8L._AC_SY450_.jpg", 149.99);

$pageHTML = new SimplePage($HTMLRenderer, "Home", "Welcome to our website!");
echo "HTML view of a simple content page:<br/>";
testingCode($pageHTML);
echo "<br/><br/>";
echo "<br/><br/>";
echo "<br/><br/>";
$pageJSON = new SimplePage($JSONRenderer, "Home", "Welcome to our website!");
echo "JSON view of a simple content page:<br/>";
testingCode($pageJSON);
echo "<br/><br/>";
echo "<br/><br/>";
echo "<br/><br/>";
$pageHTML = new ProductPage($HTMLRenderer, $product);
echo "HTML view of a product content page:<br/>";
testingCode($pageHTML);
echo "<br/><br/>";
echo "<br/><br/>";
echo "<br/><br/>";
$pageJSON = new ProductPage($JSONRenderer, $product);
echo "JSON view of a product content page:<br/>";
testingCode($pageJSON);
echo "<br/><br/>";
echo "<br/><br/>";
echo "<br/><br/>";
