<?php

namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Thumbnail;

class ThumbnailsMapper implements MapperInterface
{
    public function map($values)
    {
        $thumbnails = [];

        foreach ((array) $values as $value) {
            $entity = new Thumbnail();

            $entity->setId((int) $value['id']);
            $entity->setUrl($value['url']);

            $thumbnails[] = $entity;
        }

        return $thumbnails;
    }
}
