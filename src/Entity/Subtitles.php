<?php
namespace YoutubeDl\Entity;

class Subtitles
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var Caption[]
     */
    protected $captions;

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return Caption[]
     */
    public function getCaptions()
    {
        return $this->captions;
    }
}
