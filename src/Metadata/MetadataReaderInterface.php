<?php

declare(strict_types=1);

namespace YoutubeDl\Metadata;

interface MetadataReaderInterface
{
    /**
     * @return array<mixed>
     */
    public function read(string $file): array;
}
