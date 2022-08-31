<?php

interface TemplateFactory
{
  public function createTitleTemplate(): TitleTemplate;
  public function createPageTemplate(): PageTemplate;
  public function getRenderer(): TemplateRenderer;
}

class TwigTemplateFactory implements TemplateFactory
{
  public function createPageTemplate(): PageTemplate
  {
    return new TwigPageTemplate($this->createTitleTemplate());
  }
  public function createTitleTemplate(): TitleTemplate
  {
    return new TwigTitleTemplate();
  }

  public function getRenderer(): TemplateRenderer
  {
    return new TwigRenderer();
  }
}

class PHPTemplateFactory implements TemplateFactory
{
  public function createPageTemplate(): PageTemplate
  {
    return new PHPPageTemplate($this->createTitleTemplate());
  }
  public function createTitleTemplate(): TitleTemplate
  {
    return new PHPTitleTemplate();
  }

  public function getRenderer(): TemplateRenderer
  {
    return new PHPRenderer();
  }
}

interface TitleTemplate
{
  public function getTemplateString(): string;
}
interface PageTemplate
{
  public function getTemplateString(): string;
}
interface TemplateRenderer
{
  public function render(string $templateString, array $args = []): string;
}


class TwigRenderer implements TemplateRenderer
{
  /** 
   * @param $args: string[] 
   */
  public function render(string $templateString, array $args = []): string
  {
    return "\Twig::render($templateString, $args)";
  }
}

class PHPRenderer implements TemplateRenderer
{
  /** 
   * @param $args: string[] 
   */
  public function render(string $templateString, array $args = []): string
  {
    extract($args);
    ob_start();
    eval(" ?>" . $templateString . "<?php");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
  }
}

class TwigTitleTemplate implements TitleTemplate
{
  public function getTemplateString(): string
  {
    return "<h1>{{ title }}</h1>";
  }
}
class PHPTitleTemplate implements TitleTemplate
{
  public function getTemplateString(): string
  {
    return "<h1><?= \$title; ?></h1>";
  }
}

abstract class BasePageTemplate implements PageTemplate
{
  public function __construct(protected TitleTemplate $titleTemplate)
  {
  }
}

class TwigPageTemplate extends BasePageTemplate
{
  public function getTemplateString(): string
  {
    $renderedTitle = $this->titleTemplate->getTemplateString();
    return <<<HTML
    <div class="page">
        $renderedTitle
        <article class="content">{{ content }}</article>
    </div>
    HTML;
  }
}

class PHPPageTemplate extends BasePageTemplate
{
  public function getTemplateString(): string
  {
    $renderedTitle = $this->titleTemplate->getTemplateString();
    return <<<HTML
    <div class="page">
        $renderedTitle
        <article class="content"><?= \$content; ?></article>
    </div>
    HTML;
  }
}


class Page
{
  public function __construct(public $title, public $content)
  {
  }

  public function render(TemplateFactory $factory): string
  {
    $pageTemplate = $factory->createPageTemplate();
    $renderer = $factory->getRenderer();

    return $renderer->render($pageTemplate->getTemplateString(), [
      'title' => $this->title,
      'content' => $this->content,

    ]);
  }
}

$page = new Page("Hello World", "lipsum");

echo $page->render(new PHPTemplateFactory());

// echo $page->render(new TwigTemplateFactory());
