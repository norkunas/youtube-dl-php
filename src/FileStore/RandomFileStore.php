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
    private string $tmpPath;
    private Filesystem $filesystem;

    public function __construct(?string $tmpPath = null, ?Filesystem $filesystem = null)
    {
        $this->tmpPath = $tmpPath ?? sys_get_temp_dir().'/yt-dl';
        $this->filesystem = $filesystem ?? new Filesystem();
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
