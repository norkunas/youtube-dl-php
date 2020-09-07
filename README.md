# Youtube-dl PHP
A PHP wrapper for [youtube-dl](https://github.com/rg3/youtube-dl) tool.

[![Latest Stable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/stable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Latest Unstable Version](https://poser.pugx.org/norkunas/youtube-dl-php/v/unstable.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Total Downloads](https://poser.pugx.org/norkunas/youtube-dl-php/downloads)](https://packagist.org/packages/norkunas/youtube-dl-php)
[![Build Status](https://travis-ci.org/norkunas/youtube-dl-php.svg?branch=1.x)](https://travis-ci.org/norkunas/youtube-dl-php)
[![License](https://poser.pugx.org/norkunas/youtube-dl-php/license.svg)](https://packagist.org/packages/norkunas/youtube-dl-php)

## Install
First step is to download the [youtube-dl](http://rg3.github.io/youtube-dl/download.html).

Second step is to install the wrapper using [Composer](http://getcomposer.org/):
```
composer require norkunas/youtube-dl-php
```

## Download video
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;

$dl = new YoutubeDl([
    'continue' => true, // force resume of partially downloaded files. By default, youtube-dl will resume downloads if possible.
    'format' => 'bestvideo',
]);
// For more options go to https://github.com/rg3/youtube-dl#user-content-options

// You can set the youtube-dl binary path directly, so the library will know
// how to execute it without trying to locate it automatically. Also you can
// add it to PATH environment variable.
$dl->setBinPath('/path/to/youtube-dl');

// If you are getting some Python related errors on windows (ex.: https://github.com/norkunas/youtube-dl-php/pull/40),
// you can try to set the python path, it may help.
$dl->setPythonPath('C:\Python\python.exe');

// Set the download path where you want to store downloaded data
$dl->setDownloadPath('/home/user/downloads');

// Enable debugging
/*$dl->debug(function ($type, $buffer) {
    if (\Symfony\Component\Process\Process::ERR === $type) {
        echo 'ERR > ' . $buffer;
    } else {
        echo 'OUT > ' . $buffer;
    }
});*/
try {
    $video = $dl->download('https://www.youtube.com/watch?v=oDAw7vW7H0c');
    echo $video->getTitle(); // Will return Phonebloks
    // $video->getFile(); // \SplFileInfo instance of downloaded file
} catch (NotFoundException $e) {
    // Video not found
} catch (PrivateVideoException $e) {
    // Video is private
} catch (CopyrightException $e) {
    // The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement
} catch (\Exception $e) {
    // Failed to download
}
```

## Download only audio (requires ffmpeg or avconv and ffprobe or avprobe)
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use YoutubeDl\YoutubeDl;

$dl = new YoutubeDl([
    'extract-audio' => true,
    'audio-format' => 'mp3',
    'audio-quality' => 0, // best
    'output' => '%(title)s.%(ext)s',
]);
$dl->setDownloadPath('/home/user/downloads');

$video = $dl->download('https://www.youtube.com/watch?v=oDAw7vW7H0c');
```

## Download progress
```php
<?php
$dl->onProgress(function ($progress) {
    $percentage = $progress['percentage'];
    $size = $progress['size'];
    $speed = $progress['speed'] ?? null;
    $eta = $progress['eta'] ?? null;
    
    echo "Percentage: $percentage; Size: $size";
    if ($speed) {
        echo "; Speed: $speed";
    }
    if ($eta) {
        echo "; ETA: $eta";
    }
    // Will print: Percentage: 21.3%; Size: 4.69MiB; Speed: 4.47MiB/s; ETA: 00:01
});
```

**Disabled options which would break download:**

list-formats, list-subs, list-thumbnails, get-url, get-title, get-id, get-thumbnail, get-description, get-duration, get-filename, get-format, dump-json, dump-single-json, print-json, write-info-json (used internally), newline, no-progress, console-title, verbose, dump-pages, write-pages, print-traffic, ignore-config (used internally), all-formats, playlist-start, playlist-end, playlist-items, playlist-reverse, yes-playlist, no-playlist (used internally).
