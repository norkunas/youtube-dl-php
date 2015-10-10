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
     * @var string
     */
    protected $resolution;

    /**
     * @var int
     */
    protected $vbr;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var int
     */
    protected $languagePreference;

    /**
     * @var int
     */
    protected $quality;

    /**
     * @var int
     */
    protected $sourcePreference;

    /**
     * @var float
     */
    protected $stretchedRatio;

    /**
     * @var bool
     */
    protected $noResume;

    /**
     * @param string $format
     */
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

    /**
     * @param string $url
     */
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

    /**
     * @param array $httpHeaders
     */
    public function setHttpHeaders(array $httpHeaders)
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

    /**
     * @param string $acodec
     */
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

    /**
     * @param string $vcodec
     */
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

    /**
     * @param string $formatNote
     */
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

    /**
     * @param int $abr
     */
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

    /**
     * @param string $playerUrl
     */
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

    /**
     * @param string $ext
     */
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

    /**
     * @param int $preference
     */
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

    /**
     * @param string $formatId
     */
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

    /**
     * @param string $container
     */
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

    /**
     * @param int $width
     */
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

    /**
     * @param int $height
     */
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

    /**
     * @param int $asr
     */
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

    /**
     * @param int $tbr
     */
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

    /**
     * @param int $fps
     */
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

    /**
     * @param int $filesize
     */
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

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param int $vbr
     */
    public function setVbr($vbr)
    {
        $this->vbr = $vbr;
    }

    /**
     * @return int
     */
    public function getVbr()
    {
        return $this->vbr;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param int $languagePreference
     */
    public function setLanguagePreference($languagePreference)
    {
        $this->languagePreference = $languagePreference;
    }

    /**
     * @return int
     */
    public function getLanguagePreference()
    {
        return $this->languagePreference;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param int $sourcePreference
     */
    public function setSourcePreference($sourcePreference)
    {
        $this->sourcePreference = $sourcePreference;
    }

    /**
     * @return int
     */
    public function getSourcePreference()
    {
        return $this->sourcePreference;
    }

    /**
     * @param float $stretchedratio
     */
    public function setStretchedRatio($stretchedratio)
    {
        $this->stretchedRatio = $stretchedratio;
    }

    /**
     * @return float
     */
    public function getStretchedRatio()
    {
        return $this->stretchedRatio;
    }

    /**
     * @param bool $noResume
     */
    public function setNoResume($noResume)
    {
        $this->noResume = $noResume;
    }

    /**
     * @return bool
     */
    public function getNoResume()
    {
        return $this->noResume;
    }
}
