<?php

declare(strict_types=1);

namespace YoutubeDl\Exception;

class FileException extends YoutubeDlException
{
    public static function cannotRead(string $file): self
    {
        return new self(sprintf('Cannot read "%s" file.', $file));
    }
}
