<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Category extends AbstractEntity
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->get('title');
    }
}
