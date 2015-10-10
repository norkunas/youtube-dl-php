<?php

namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Caption;
use YoutubeDl\Entity\Subtitles;

class SubtitlesMapper implements MapperInterface
{
    public function map($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $output = [];

        foreach ($data as $lang => $subtitles) {
            $entity = new Subtitles();

            $entity->setLocale($lang);
            $entity->setCaptions($this->parseCaptions($subtitles));

            $output[] = $entity;
        }

        return $output;
    }

    protected function parseCaptions($subtitles)
    {
        $captions = [];

        foreach (array_filter(explode("\n\n", $subtitles)) as $subtitle) {
            $entity = new Caption();

            $parts = explode("\n", $subtitle);
            $times = explode(' --> ', $parts[1]);

            $entity->setIndex((int) $parts[0]);
            if ($start = \DateTime::createFromFormat('H:i:s,u', $times[0])) {
                $entity->setStart($start);
            }
            if ($end = \DateTime::createFromFormat('H:i:s,u', $times[1])) {
                $entity->setEnd($end);
            }
            $entity->setCaption($parts[2]);

            $captions[] = $entity;
        }

        return $captions;
    }
}
