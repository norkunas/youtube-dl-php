# Youtube-dl PHP
A PHP wrapper for [youtube-dl](https://github.com/ytdl-org/youtube-dl) or [yt-dlp](https://github.com/yt-dlp/yt-dlp).

[![Latest Stable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/stable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Latest Unstable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/unstable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Total Downloads](https://poser.pugx.org/norkunas/youtube-dl-php/downloads)](https://packagist.org/packages/norkunas/youtube-dl-php)
![CI Status](https://github.com/norkunas/youtube-dl-php/workflows/CI/badge.svg?branch=master)
[![License](https://poser.pugx.org/norkunas/youtube-dl-php/license.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)

## Install
First step is to download the [youtube-dl](https://ytdl-org.github.io/youtube-dl/download.html) or [yt-dlp](https://github.com/yt-dlp/yt-dlp#installation).

Second step is to install the wrapper using [Composer](http://getcomposer.org/):
```
composer require norkunas/youtube-dl-php:dev-master
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
        ->downloadPath('/path/to/downloads')
        ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
);

foreach ($collection->getVideos() as $video) {
    if ($video->getError() !== null) {
        echo "Error downloading video: {$video->getError()}.";
    } else {
        echo $video->getTitle(); // Will return Phonebloks
        // $video->getFile(); // \SplFileInfo instance of downloaded file
    }
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
        ->downloadPath('/path/to/downloads')
        ->extractAudio(true)
        ->audioFormat('mp3')
        ->audioQuality('0') // best
        ->output('%(title)s.%(ext)s')
        ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
);

foreach ($collection->getVideos() as $video) {
    if ($video->getError() !== null) {
        echo "Error downloading video: {$video->getError()}.";
    } else {
        $video->getFile(); // audio file
    }
}
```

## Download progress
```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\YoutubeDl;

$yt = new YoutubeDl();
$yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
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

## Custom Process Instantiation

```php
<?php

declare(strict_types=1);

namespace App\YoutubeDl;

use Symfony\Component\Process\Process;
use YoutubeDl\Process\ProcessBuilderInterface;

class ProcessBuilder implements ProcessBuilderInterface
{
    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process
    {
        $process = new Process([$binPath, $pythonPath, ...$arguments]);
        // Set custom timeout or customize other things..
        $process->setTimeout(60);

        return $process;
    }
}
```

```php
<?php

declare(strict_types=1);

use App\YoutubeDl\ProcessBuilder;
use YoutubeDl\YoutubeDl;

$processBuilder = new ProcessBuilder();

// Provide your custom process builder as the first argument.
$yt = new YoutubeDl($processBuilder);
```


## Questions?

If you have any questions please [open a discussion](https://github.com/norkunas/youtube-dl-php/discussions/new).

## License

This library is released under the MIT License. See the bundled [LICENSE](https://github.com/norkunas/youtube-dl-php/blob/master/LICENSE) file for details.
