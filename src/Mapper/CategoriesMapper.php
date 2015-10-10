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
            $entity->setTitle($value);

            $categories[] = $entity;
        }

        return $categories;
    }
}
