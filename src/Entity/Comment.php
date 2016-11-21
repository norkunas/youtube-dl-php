<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Comment extends AbstractEntity
{
    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->get('author');
    }

    /**
     * @return string
     */
    public function getAuthorId()
    {
        return $this->get('author_id');
    }

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
    public function getHtml()
    {
        return $this->get('html');
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->get('text');
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->get('timestamp');
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->get('parent');
    }
}
