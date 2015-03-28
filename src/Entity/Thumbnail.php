<?php
namespace YoutubeDl\Entity;

class Thumbnail
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $url;

    /**
     * Get thumbnail ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get thumbnail URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
