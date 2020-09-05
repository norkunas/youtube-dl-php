<?php

declare(strict_types=1);

namespace YoutubeDl\FileStore;

interface FileStoreInterface
{
    /**
     * Creates the directory to store files and returns it's path.
     */
    public function createPath(): string;
}
