<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\FileStore;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;
use YoutubeDl\FileStore\StaticFileStore;

final class StaticFileStoreTest extends TestCase
{
    public function testCreatePath(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot($root = new vfsStreamDirectory('yt-dl'));

        $fileStore = new StaticFileStore($root->url().'/yt-dl');

        static::assertDirectoryExists($fileStore->createPath());
    }
}
