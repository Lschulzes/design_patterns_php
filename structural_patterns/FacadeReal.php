<?php

class YoutubeDownloader
{
  protected $youtube;
  protected $ffmpeg;

  public function __construct(string $youtubeApiKey)
  {
    $this->youtube = new Youtube($youtubeApiKey);
    $this->ffmpeg = new FFMpeg();
  }

  public function donwloadVideo(string $url): void
  {
    echo "Fetching video metadata from youtube... <br>";
    // $title = $this->youtube->fetchVideo($url)->getTitle();
    echo "Saving video file to a temporary file...\n";
    // $this->youtube->saveAs($url, "video.mpg");

    echo "Processing source video...\n";
    // $video = $this->ffmpeg->open('video.mpg');
    echo "Normalizing and resizing the video to smaller dimensions...\n";
    // $video
    //     ->filters()
    //     ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
    //     ->synchronize();
    echo "Capturing preview image...\n";
    // $video
    //     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
    //     ->save($title . 'frame.jpg');
    echo "Saving video in target formats...\n";
    // $video
    //     ->save(new FFMpeg\Format\Video\X264(), $title . '.mp4')
    //     ->save(new FFMpeg\Format\Video\WMV(), $title . '.wmv')
    //     ->save(new FFMpeg\Format\Video\WebM(), $title . '.webm');
    echo "Done!\n";
  }
}

class YouTube
{
  public function fetchVideo(): string
  { /* ... */
  }

  public function saveAs(string $path): void
  { /* ... */
  }

  // ...more methods and classes...
}

/**
 * The FFmpeg subsystem (a complex video/audio conversion library).
 */
class FFMpeg
{
  public static function create(): FFMpeg
  { /* ... */
  }

  public function open(string $video): void
  { /* ... */
  }

  // ...more methods and classes... RU: ...дополнительные методы и классы...
}

class FFMpegVideo
{
  public function filters(): self
  { /* ... */
  }

  public function resize(): self
  { /* ... */
  }

  public function synchronize(): self
  { /* ... */
  }

  public function frame(): self
  { /* ... */
  }

  public function save(string $path): self
  { /* ... */
  }

  // ...more methods and classes... RU: ...дополнительные методы и классы...
}


/**
 * The client code does not depend on any subsystem's classes. Any changes
 * inside the subsystem's code won't affect the client code. You will only need
 * to update the Facade.
 */
function clientCode(YouTubeDownloader $facade)
{
  // ...

  $facade->downloadVideo("https://www.youtube.com/watch?v=QH2-TGUlwu4");

  // ...
}

$facade = new YouTubeDownloader("APIKEY-XXXXXXXXX");
clientCode($facade);
