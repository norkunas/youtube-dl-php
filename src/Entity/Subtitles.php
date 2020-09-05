<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Subtitles extends AbstractEntity
{
    public function getUrl(): ?string
    {
        return $this->get('url');
    }

    public function getExt(): ?string
    {
        return $this->get('ext');
    }
}
