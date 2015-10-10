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

    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function setHttpHeaders($httpHeaders)
    {
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    public function setAcodec($acodec)
    {
        $this->acodec = $acodec;
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->acodec;
    }

    public function setVcodec($vcodec)
    {
        $this->vcodec = $vcodec;
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->vcodec;
    }

    public function setFormatNote($formatNote)
    {
        $this->formatNote = $formatNote;
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->formatNote;
    }

    public function setAbr($abr)
    {
        $this->abr = $abr;
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->abr;
    }

    public function setPlayerUrl($playerUrl)
    {
        $this->playerUrl = $playerUrl;
    }

    /**
     * @return string
     */
    public function getPlayerUrl()
    {
        return $this->playerUrl;
    }

    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    public function setPreference($preference)
    {
        $this->preference = $preference;
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->preference;
    }

    public function setFormatId($formatId)
    {
        $this->formatId = $formatId;
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->formatId;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function setAsr($asr)
    {
        $this->asr = $asr;
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->asr;
    }

    public function setTbr($tbr)
    {
        $this->tbr = $tbr;
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->tbr;
    }

    public function setFps($fps)
    {
        $this->fps = $fps;
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->fps;
    }

    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
}
