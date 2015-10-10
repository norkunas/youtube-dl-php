<?php

namespace YoutubeDl\Mapper;

class AnnotationsMapper implements MapperInterface
{
    public function map($data)
    {
        try {
            libxml_use_internal_errors(true);

            $obj = new \SimpleXMLElement($data);
            libxml_clear_errors();

            if ($obj) {
                return $obj;
            }
        } catch (\Exception $e) {
            // If for some reason annotations can't be mapped then just ignore this
        }

        return null;
    }
}
