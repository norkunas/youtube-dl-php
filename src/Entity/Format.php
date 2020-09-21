<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Format extends AbstractEntity
{
    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->get('format');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->get('http_headers', []);
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->get('acodec');
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->get('vcodec');
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->get('format_note');
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->get('abr');
    }

    /**
     * @return string
     */
    public function getPlayerUrl()
    {
        return $this->get('player_url');
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->get('ext');
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->get('preference');
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->get('format');
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->get('container');
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->get('width');
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->get('height');
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->get('asr');
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->get('tbr');
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->get('fps');
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->get('filesize');
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->get('resolution');
    }

    /**
     * @return int
     */
    public function getVbr()
    {
        return $this->get('vbr');
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->get('protocol');
    }

    /**
     * @return int
     */
    public function getLanguagePreference()
    {
        return $this->get('language_preference');
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->get('quality');
    }

    /**
     * @return int
     */
    public function getSourcePreference()
    {
        return $this->get('source_preference');
    }

    /**
     * @return float
     */
    public function getStretchedRatio()
    {
        return $this->get('stretched_ratio');
    }

    /**
     * @return bool
     */
    public function getNoResume()
    {
        return $this->get('no_resume');
    }
}
