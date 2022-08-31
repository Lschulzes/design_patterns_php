<?php
class CsvIterator  implements \Iterator
{
  const ROW_SIZE = 4096;
  protected $filePointer = null;
  protected $currentElement = null;
  protected $rowCounter = null;
  public function __construct($file, public string $delimiter = ",")
  {
    try {
      $this->filePointer = fopen($file, 'rb');
      $this->delimiter = $delimiter;
    } catch (\Exception $e) {
      throw new Exception("The file '" . $file . "' cannot be read.", 1);
    }
  }

  public function rewind()
  {
    $this->rowCounter = 0;
    rewind($this->filePointer);
  }

  public function current()
  {
    $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
    $this->rowCounter++;

    return $this->currentElement;
  }

  public function key()
  {
    return $this->rowCounter;
  }
  public function next(): bool
  {
    if (is_resource($this->filePointer)) {
      return !feof($this->filePointer);
    }

    return false;
  }

  /**
   * This method checks if the next row is a valid row.
   *
   * @return bool If the next row is a valid row.
   */
  public function valid(): bool
  {
    if (!$this->next()) {
      if (is_resource($this->filePointer)) {
        fclose($this->filePointer);
      }

      return false;
    }

    return true;
  }
}

$csv = new CsvIterator(__DIR__ . '/pets.csv');
$encoded;

foreach ($csv as $key => $row) {
  foreach ($row as &$value) {
    echo $value . " | ";
  }
  echo "<br>";
  echo "<br>";
}
