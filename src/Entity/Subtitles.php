<?php

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
