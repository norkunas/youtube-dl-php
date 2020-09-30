# Youtube-dl PHP
A PHP wrapper for [youtube-dl](https://github.com/ytdl-org/youtube-dl) tool.

[![Latest Stable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/stable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Latest Unstable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/unstable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Total Downloads](https://poser.pugx.org/norkunas/youtube-dl-php/downloads)](https://packagist.org/packages/norkunas/youtube-dl-php)
![CI Status](https://github.com/norkunas/youtube-dl-php/workflows/CI/badge.svg?branch=master)
[![License](https://poser.pugx.org/norkunas/youtube-dl-php/license.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)

##### :warning: You are perusing documentation for non delivered form yet. In the event that you have to utilize stable branch please read [v1 documentation]
Link: (https://github.com/norkunas/youtube-dl-php/tree/1.x#youtube-dl-php).

## Installation Steps
Step 1:
Download the [youtube-dl]
Link:(https://ytdl-org.github.io/youtube-dl/download.html).

Step 2:
Install the wrapper using [Composer]
Link: (http://getcomposer.org/):
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
        ->extractAudio(true)
        ->audioFormat('mp3')
        ->audioQuality(0) // best
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

## Custom Process of Instantiation

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

## File Stores

Currently there are two prepared FileStores:
- `YoutubeDl\FileStore\RandomFileStore` - this store is used by default to
download to random subdirectory of temporary dir which can be provided as a
first argument, which defaults to `sys_get_tmp_dir()`.
- `YoutubeDl\FileStore\StaticFileStore` - this store can be used to always
download to a static directory. Also it is used fo tests.

### Custom FileStore

```php
<?php

declare(strict_types=1);

namespace App\YoutubeDl;

use YoutubeDl\FileStore\FileStoreInterface;

class TimestampFileStore implements FileStoreInterface
{
    public function createPath() : string
    {
        $dir = '/home/norkunas/yt-downloads';

        if (!mkdir($dir) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        $subDir = $dir.'/'.time();

        if (!mkdir($subDir) && !is_dir($subDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $subDir));
        }

        return $subDir;
    }
}
```

```php
<?php

declare(strict_types=1);

use App\YoutubeDl\TimestampFileStore;
use YoutubeDl\YoutubeDl;

$fileStore = new TimestampFileStore();

// Provide your custom process builder as the first argument.
$yt = new YoutubeDl(/* $processBuilder */null, /* $metadataReader */null, /* $filesystem */null, $fileStore);
// or
$yt->setFileStore($fileStore);
```
