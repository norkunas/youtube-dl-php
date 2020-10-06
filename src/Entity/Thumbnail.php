<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Thumbnail extends AbstractEntity
{
    public function getId(): ?string
    {
        return $this->get('id');
    }

    public function getUrl(): ?string
    {
        return $this->get('url');
    }

    public function getPreference(): ?int
    {
        return $this->get('preference');
    }

    public function getWidth(): ?int
    {
        return $this->get('width');
    }

    public function getHeight(): ?int
    {
        return $this->get('height');
    }

    public function getResolution(): ?string
    {
        return $this->get('resolution');
    }
}
