<?php

declare(strict_types=1);

namespace YoutubeDl\Metadata;

interface MetadataReaderInterface
{
    public function read(string $file): array;
}
