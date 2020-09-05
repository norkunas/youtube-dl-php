<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Extractor extends AbstractEntity
{
    public function getTitle(): string
    {
        return $this->get('title');
    }
}
