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
    protected $captions = [];

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param Caption[] $captions
     */
    public function setCaptions(array $captions)
    {
        $this->captions = $captions;
    }

    /**
     * @return Caption[]
     */
    public function getCaptions()
    {
        return $this->captions;
    }
}
