<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Thumbnail extends AbstractEntity
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->get('preference');
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->get('width');
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->get('height');
    }
}
