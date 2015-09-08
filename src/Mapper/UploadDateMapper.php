<?php

namespace YoutubeDl\Mapper;

class UploadDateMapper implements MapperInterface
{
    public function map($data)
    {
        if ($date = \DateTime::createFromFormat('Ymd', $data)) {
            return $date;
        }

        return null;
    }
}
