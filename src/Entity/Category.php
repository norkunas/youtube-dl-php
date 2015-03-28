<?php
namespace YoutubeDl\Entity;

class Category
{
    /**
     * @var string
     */
    protected $title;

    /**
     * Get category title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
