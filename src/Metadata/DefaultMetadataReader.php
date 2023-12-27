<?php

declare(strict_types=1);

namespace YoutubeDl\Metadata;

use LogicException;
use YoutubeDl\Exception\FileException;

use const JSON_THROW_ON_ERROR;

use function file_get_contents;
use function get_debug_type;
use function json_decode;

class DefaultMetadataReader implements MetadataReaderInterface
{
    public function read(string $file): array
    {
        $content = file_get_contents($file);

        if ($content === false) {
            throw FileException::cannotRead($file);
        }

        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($decoded)) {
            throw new LogicException(sprintf('Expected to read metadata as an array, got "%s".', get_debug_type($decoded)));
        }

        return $decoded;
    }
}
