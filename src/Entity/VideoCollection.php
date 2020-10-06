<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class VideoCollection extends AbstractEntity
{
    public function get(string $key, $default = null): ?Video
    {
        return parent::get($key, $default);
    }

    /**
     * @return Video[]
     */
    public function getVideos(): array
    {
        return $this->elements;
    }

    protected function convert(array $data): array
    {
        return $data;
    }
}
