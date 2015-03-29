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
        } catch (\Exception $e) { }

        return null;
    }
}
