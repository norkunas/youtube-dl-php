<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\FileStore;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use YoutubeDl\FileStore\RandomFileStore;

final class RandomFileStoreTest extends TestCase
{
    public function testCreatePath(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot($root = new vfsStreamDirectory('yt-dl'));

        $fileStore = new RandomFileStore($root->url().'/yt-dl', new Filesystem());

        static::assertDirectoryExists($fileStore->createPath());
    }
}
