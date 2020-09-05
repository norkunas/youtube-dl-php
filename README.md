# Youtube-dl PHP
A PHP wrapper for [youtube-dl](https://github.com/ytdl-org/youtube-dl) tool.

[![Latest Stable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/stable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Latest Unstable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/unstable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Total Downloads](https://poser.pugx.org/norkunas/youtube-dl-php/downloads)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Build Status](https://travis-ci.org/norkunas/youtube-dl-php.svg?branch=master)](https://travis-ci.org/norkunas/youtube-dl-php)
[![License](https://poser.pugx.org/norkunas/youtube-dl-php/license.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)

## Install
First step is to download the [youtube-dl](https://ytdl-org.github.io/youtube-dl/download.html).

Second step is to install the wrapper using [Composer](http://getcomposer.org/):
```
composer require norkunas/youtube-dl-php
```

## Download video
```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

$yt = new YoutubeDl();

$collection = $yt->download(
    Options::create()
        ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
);

foreach ($collection->getVideos() as $video) {
    echo $video->getTitle(); // Will return Phonebloks
    // $video->getFile(); // \SplFileInfo instance of downloaded file
}

```

## Download only audio (requires ffmpeg or avconv and ffprobe or avprobe)
```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

$yt = new YoutubeDl();
$collection = $yt->download(
    Options::create()
        ->extractAudio(true)
        ->audioFormat('mp3')
        ->audioQuality(0) // best
        ->output('%(title)s.%(ext)s')
        ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
);

foreach ($collection->getVideos() as $video) {
    $video->getFile(); // audio file
}
```

## Download progress
```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\YoutubeDl;

$yt = new YoutubeDl();
$yt->onProgress(static function (string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
    echo "Download file: $progressTarget; Percentage: $percentage; Size: $size";
    if ($speed) {
        echo "; Speed: $speed";
    }
    if ($eta) {
        echo "; ETA: $eta";
    }
    if ($totalTime !== null) {
        echo "; Downloaded in: $totalTime";
    }
});
```
