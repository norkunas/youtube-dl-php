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
            $reflection = new \ReflectionObject($entity);

            $id = $reflection->getProperty('id');
            $id->setAccessible(true);
            $id->setValue($entity, (int) $value['id']);

            $url = $reflection->getProperty('url');
            $url->setAccessible(true);
            $url->setValue($entity, $value['url']);

            $thumbnails[] = $entity;
        }

        return $thumbnails;
    }
}
