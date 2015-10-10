<?php

namespace YoutubeDl\Mapper;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class PropertyAccessorAwareMapper implements MapperInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;

    /**
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }
}
