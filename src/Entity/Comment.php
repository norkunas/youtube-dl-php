<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Comment extends AbstractEntity
{
    public function getAuthor(): ?string
    {
        return $this->get('author');
    }

    public function getAuthorId(): ?string
    {
        return $this->get('author_id');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->get('id');
    }

    public function getHtml(): ?string
    {
        return $this->get('html');
    }

    public function getText(): ?string
    {
        return $this->get('text');
    }

    public function getTimestamp(): ?int
    {
        return $this->get('timestamp');
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->get('parent');
    }
}
