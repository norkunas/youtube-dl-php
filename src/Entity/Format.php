<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Format extends AbstractEntity
{
    public function getFormat(): ?string
    {
        return $this->get('format');
    }

    public function getUrl(): ?string
    {
        return $this->get('url');
    }

    /**
     * @return array<string, string>
     */
    public function getHttpHeaders(): array
    {
        return $this->get('http_headers', []);
    }

    public function getAcodec(): ?string
    {
        return $this->get('acodec');
    }

    public function getVcodec(): ?string
    {
        return $this->get('vcodec');
    }

    public function getFormatNote(): ?string
    {
        return $this->get('format_note');
    }

    public function getAbr(): ?int
    {
        return $this->get('abr');
    }

    public function getPlayerUrl(): ?string
    {
        return $this->get('player_url');
    }

    public function getExt(): ?string
    {
        return $this->get('ext');
    }

    public function getPreference(): ?int
    {
        return $this->get('preference');
    }

    public function getFormatId(): ?string
    {
        return $this->get('format_id');
    }

    public function getContainer(): ?string
    {
        return $this->get('container');
    }

    public function getWidth(): ?int
    {
        return $this->get('width');
    }

    public function getHeight(): ?int
    {
        return $this->get('height');
    }

    public function getAsr(): ?int
    {
        return $this->get('asr');
    }

    public function getTbr(): ?float
    {
        return $this->get('tbr');
    }

    public function getFps(): ?float
    {
        return $this->get('fps');
    }

    public function getFilesize(): ?int
    {
        return $this->get('filesize');
    }

    public function getResolution(): ?string
    {
        return $this->get('resolution');
    }

    public function getVbr(): ?int
    {
        return $this->get('vbr');
    }

    public function getProtocol(): ?string
    {
        return $this->get('protocol');
    }

    public function getLanguagePreference(): ?int
    {
        return $this->get('language_preference');
    }

    public function getQuality(): ?int
    {
        return $this->get('quality');
    }

    public function getSourcePreference(): ?int
    {
        return $this->get('source_preference');
    }

    public function getStretchedRatio(): ?float
    {
        return $this->get('stretched_ratio');
    }

    public function getNoResume(): bool
    {
        return $this->get('no_resume');
    }
}
