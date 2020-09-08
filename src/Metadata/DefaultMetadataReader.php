<?php

declare(strict_types=1);

namespace YoutubeDl\Metadata;

use YoutubeDl\Exception\FileException;
use const JSON_THROW_ON_ERROR;
use function file_get_contents;
use function json_decode;

class DefaultMetadataReader implements MetadataReaderInterface
{
    public function read(string $file): array
    {
        $content = file_get_contents($file);

        if ($content === false) {
            throw FileException::cannotRead($file);
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}
