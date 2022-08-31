<?php

interface InputFormat
{
  public function formatText(string $text): string;
}

class TextInput implements InputFormat
{
  public function formatText(string $text): string
  {
    return $text;
  }
}

class TextFormat implements InputFormat
{
  public function __construct(protected InputFormat $inputFormat)
  {
  }

  public function formatText(string $text): string
  {
    return $this->inputFormat->formatText($text);
  }
}

class PlainTextFilter extends TextFormat
{
  public function formatText(string $text): string
  {
    $text = parent::formatText($text);
    return strip_tags($text);
  }
}

class DangerousHTMLTagsFilter extends TextFormat
{
  private $dangerousTagPatterns = [
    "|<script.*?>([\s\S]*)?</script>|i", // ...
  ];

  private $dangerousAttributes = [
    "onclick", "onkeypress", // ...
  ];

  public function formatText(string $text): string
  {
    $text = parent::formatText($text);
    foreach ($this->dangerousTagPatterns as $pattern) $text = preg_replace($pattern, "", $text);

    foreach ($this->dangerousAttributes as $attribute) {
      $text = preg_replace_callback('|<(.*?)>|', function ($matches) use ($attribute) {
        $result = preg_replace("|$attribute=|i", '', $matches[1]);
        return "<" . $result . ">";
      }, $text);

      return $text;
    }
  }
}

class MarkdownFormat extends TextFormat
{
  public function formatText(string $text): string
  {
    $text = parent::formatText($text);

    $chunks = preg_split('|\n\n|', $text);
    foreach ($chunks as &$chunk) {
      // Format headers.
      if (preg_match('|^#+|', $chunk)) {
        $chunk = preg_replace_callback('|^(#+)(.*?)$|', function ($matches) {
          $h = strlen($matches[1]);
          return "<h$h>" . trim($matches[2]) . "</h$h>";
        }, $chunk);
      } // Format paragraphs.
      else {
        $chunk = "<p>$chunk</p>";
      }
    }
    $text = implode("\n\n", $chunks);

    // Format inline elements.
    $text = preg_replace("|__(.*?)__|", '<strong>$1</strong>', $text);
    $text = preg_replace("|\*\*(.*?)\*\*|", '<strong>$1</strong>', $text);
    $text = preg_replace("|_(.*?)_|", '<em>$1</em>', $text);
    $text = preg_replace("|\*(.*?)\*|", '<em>$1</em>', $text);

    return $text;
  }
}


function displayCommentAsAWebsite(InputFormat $format, string $text)
{
  // ..

  echo $format->formatText($text);

  // ..
}

/**
 * Input formatters are very handy when dealing with user-generated content.
 * Displaying such content "as is" could be very dangerous, especially when
 * anonymous users can generate it (e.g. comments). Your website is not only
 * risking getting tons of spammy links but may also be exposed to XSS attacks.
 */
$dangerousComment = <<<HERE
Hello! Nice blog post!
Please visit my <a href='http://www.iwillhackyou.com'>homepage</a>.
<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

/**
 * Naive comment rendering (unsafe).
 */
$naiveInput = new TextInput();
echo "Website renders comments without filtering (unsafe):<br>";
displayCommentAsAWebsite($naiveInput, $dangerousComment);
echo "<br><br><br>";

/**
 * Filtered comment rendering (safe).
 */
$filteredInput = new PlainTextFilter($naiveInput);
echo "Website renders comments after stripping all tags (safe):<br>";
displayCommentAsAWebsite($filteredInput, $dangerousComment);
echo "<br><br><br>";


/**
 * Decorator allows stacking multiple input formats to get fine-grained control
 * over the rendered content.
 */
$dangerousForumPost = <<<HERE
# Welcome

This is my first post on this **gorgeous** forum.

<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;

/**
 * Naive post rendering (unsafe, no formatting).
 */
$naiveInput = new TextInput();
echo "Website renders a forum post without filtering and formatting (unsafe, ugly):<br>";
displayCommentAsAWebsite($naiveInput, $dangerousForumPost);
echo "<br><br><br>";

/**
 * Markdown formatter + filtering dangerous tags (safe, pretty).
 */
$text = new TextInput();
$markdown = new MarkdownFormat($text);
$filteredInput = new DangerousHTMLTagsFilter($markdown);
echo "Website renders a forum post after translating markdown markup" .
  " and filtering some dangerous HTML tags and attributes (safe, pretty):<br>";
displayCommentAsAWebsite($filteredInput, $dangerousForumPost);
echo "<br><br><br>";
