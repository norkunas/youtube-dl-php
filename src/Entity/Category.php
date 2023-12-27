<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

use function is_array;

class Category extends AbstractEntity
{
    /**
     * @param string|array{title: string} $category
     */
    public function __construct($category)
    {
        parent::__construct(is_array($category) ? $category : ['title' => $category]);
    }

    public function getTitle(): ?string
    {
        return $this->get('title');
    }
}
