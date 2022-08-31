<?php

/**
 * Prototype
 */
class Page
{
  private $date;
  private $comments = [];
  public function __construct(private string $title, private string $body, private Author $author)
  {
    $this->author->addToPage($this);
    $this->date = new \DateTime();
  }

  public function addComment(string $comment): void
  {
    $this->comments[] = $comment;
  }

  public function __clone()
  {
    $this->title = "Copy of " . $this->title;
    $this->author->addToPage($this);
    $this->comments = [];
    $this->date = new \DateTime();
  }
}

class Author
{
  private $pages = [];
  public function __construct(private string $name)
  {
  }

  public function addToPage(Page $page): void
  {
    $this->pages[] = $page;
  }
}

(function () {
  $author = new Author("John Smith");
  $page = new Page("Tip of the day", "Keep calm and carry on.", $author);
  $page->addComment("Nice tip, thanks!");
  $draft = clone $page;
  echo "Dump of the clone <br/>";
  echo "<pre>";
  print_r($page);
  print_r($draft);
  echo "</pre>";
})();
