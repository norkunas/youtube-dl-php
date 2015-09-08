<?php

namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Category;

class CategoriesMapper implements MapperInterface
{
    public function map($values)
    {
        $categories = [];

        foreach ((array) $values as $value) {
            $entity = new Category();
            $reflection = new \ReflectionObject($entity);

            $title = $reflection->getProperty('title');
            $title->setAccessible(true);
            $title->setValue($entity, $value);

            $categories[] = $entity;
        }

        return $categories;
    }
}
