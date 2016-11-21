<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Subtitles extends AbstractEntity
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->get('ext');
    }
}
