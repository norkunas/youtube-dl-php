<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class SubListItem extends AbstractEntity
{
    public function getLanguage(): string
    {
        return $this->get('language');
    }

    /**
     * @return list<non-empty-string>
     */
    public function getFormats(): array
    {
        return $this->get('formats');
    }

    public function isAutoCaption(): bool
    {
        return $this->get('auto_caption', false);
    }
}
