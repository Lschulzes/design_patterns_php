<?php

interface Downloader
{
  public function download(string $url): string;
}

class SimpleDownload implements Downloader
{
  public function download(string $url): string
  {
    echo "Downloading a file from the Internet.<br>";
    $result = file_get_contents($url);
    echo "Downloaded bytes: " . strlen($result) . "<br>";
    return $result;
  }
}

class ChachingDownloader implements Downloader
{
  private $cache = [];
  public function __construct(private SimpleDownload $donwloader)
  {
  }

  public function download(string $url): string
  {
    if (isset($this->cache[$url])) {
      echo "CacheProxy HIT. Retrieving result from cache.<br>";
    }
    if (!isset($this->cache[$url])) {
      echo "CacheProxy MISS. ";
      $result = $this->donwloader->download($url);
      $this->cache[$url] = $result;
    }


    return $this->cache[$url];
  }
}


function proxy(Downloader $subject)
{
  for ($i = 0; $i < 20; $i++) echo $subject->download("https://google.com/");
}
// echo "Executing client code with real subject:<br>";
$realSubject = new SimpleDownload();
// proxy($realSubject);
// echo "<br>";

echo "Executing the same client code with a proxy:<br>";
$proxySubject = new ChachingDownloader($realSubject);
proxy($proxySubject);
