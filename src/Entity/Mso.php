<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Mso extends AbstractEntity
{
    public function getCode(): string
    {
        return $this->get('code');
    }

    public function getName(): string
    {
        return $this->get('name');
    }
}
