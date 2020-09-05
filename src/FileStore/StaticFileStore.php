<?php

declare(strict_types=1);

namespace YoutubeDl\FileStore;

use Symfony\Component\Filesystem\Filesystem;

final class StaticFileStore implements FileStoreInterface
{
    private string $path;
    private Filesystem $filesystem;

    public function __construct(string $path, Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function createPath(): string
    {
        $this->filesystem->mkdir($this->path);

        return $this->path;
    }
}
