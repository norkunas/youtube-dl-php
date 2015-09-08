<?php

namespace YoutubeDl\Entity;

class Format
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $httpHeaders;

    /**
     * @var string
     */
    protected $acodec;

    /**
     * @var string
     */
    protected $vcodec;

    /**
     * @var string
     */
    protected $formatNote;

    /**
     * @var int
     */
    protected $abr;

    /**
     * @var string
     */
    protected $playerUrl;

    /**
     * @var string
     */
    protected $ext;

    /**
     * @var int
     */
    protected $preference;

    /**
     * @var string
     */
    protected $formatId;

    /**
     * @var string
     */
    protected $container;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $asr;

    /**
     * @var int
     */
    protected $tbr;

    /**
     * @var int
     */
    protected $fps;

    /**
     * @var int
     */
    protected $filesize;

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->acodec;
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->vcodec;
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->formatNote;
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * @return string
     */
    public function getPlayerUrl()
    {
        return $this->playerUrl;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->formatId;
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->asr;
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->tbr;
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
}
