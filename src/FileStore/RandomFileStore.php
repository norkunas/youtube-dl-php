<?php

declare(strict_types=1);

namespace YoutubeDl\FileStore;

use Symfony\Component\Filesystem\Filesystem;
use function bin2hex;
use function random_bytes;
use function sprintf;
use function sys_get_temp_dir;

final class RandomFileStore implements FileStoreInterface
{
    private Filesystem $filesystem;
    private string $tmpPath;

    public function __construct(Filesystem $filesystem, ?string $tmpPath = null)
    {
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->tmpPath = $tmpPath ?? sys_get_temp_dir();
    }

    /**
     * {@inheritdoc}
     */
    public function createPath(): string
    {
        $path = sprintf('%s/%s', $this->tmpPath, bin2hex(random_bytes(5)));

        $this->filesystem->mkdir($path);

        return $path;
    }
}
