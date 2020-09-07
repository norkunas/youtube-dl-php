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
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('yt-dl'));

        $fileStore = new RandomFileStore(new Filesystem(), vfsStreamWrapper::getRoot()->url().'/yt-dl');

        static::assertDirectoryExists($fileStore->createPath());
    }
}
