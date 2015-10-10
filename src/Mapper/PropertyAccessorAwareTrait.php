<?php

namespace YoutubeDl\Mapper;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

trait PropertyAccessorAwareTrait
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
