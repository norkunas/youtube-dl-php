<?php
require_once("vendor/autoload.php");

/*$loop = \React\EventLoop\Factory::create();
$timer = $loop->addPeriodicTimer(1, function () {
  static $i = 0;
  echo "Loop ".++$i."\n";
});
$loop->addTimer(1, function () use ($timer, $loop) {
  $dl = new \YoutubeDl\YoutubeDl([
    "extract-audio" => true,
    "audio-format" => "mp3",
    "audio-quality" => 0,
    "output" => "%(id)s.%(ext)s"
  ]);
  $dl->setBinPath(__DIR__.DIRECTORY_SEPARATOR."youtube-dl");
  $dl->setDownloadPath("download");
  $dl->downloadAsync("https://www.youtube.com/watch?v=ydc1xPm7Usw", $loop)->then(function (\YoutubeDl\Entity\Video $video) use ($timer) {
    echo "Done, ".$video->getFilename()."\n";
    $timer->cancel();
  }, function (Exception $e) use ($timer) {
    echo get_class($e).": ".$e->getMessage()."\n";
    $timer->cancel();
  });
});
$loop->run();*/

$dl = new \YoutubeDl\YoutubeDl([
  "extract-audio" => true,
  "audio-format" => "mp3",
  "audio-quality" => 0,
  "output" => "%(id)s.%(ext)s"
]);
$dl->setBinPath(__DIR__.DIRECTORY_SEPARATOR."youtube-dl");
$dl->setDownloadPath("download");
$dl->download("https://www.youtube.com/watch?v=wM8ytYa5sHw");
/*

try {
  $video = $dl->download("https://www.youtube.com/watch?v=ydc1xPm7Ubw");
  echo "OK\n";
}
catch (Exception $e) {
  echo "Exception ".$e->getMessage()."\n";
}*/