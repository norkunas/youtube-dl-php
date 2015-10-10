<?php

namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Format;

class FormatsMapper extends PropertyAccessorAwareMapper
{
    public function map($data)
    {
        $formats = [];

        foreach ($data as $format) {
            $entity = new Format();

            foreach ($format as $field => $value) {
                try {
                    $this->propertyAccessor->setValue($entity, $field, $value);
                } catch (\Exception $e) {
                    // Ignore if property does not exist
                }
            }

            $formats[] = $entity;
        }

        return $formats;
    }
}
