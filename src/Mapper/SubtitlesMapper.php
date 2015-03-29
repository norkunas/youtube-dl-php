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
            $reflection = new \ReflectionObject($entity);

            $locale = $reflection->getProperty('locale');
            $locale->setAccessible(true);
            $locale->setValue($entity, $lang);

            $captions = $reflection->getProperty('captions');
            $captions->setAccessible(true);
            $captions->setValue($entity, $this->parseCaptions($subtitles));

            $output[] = $entity;
        }

        return $output;
    }

    protected function parseCaptions($subtitles)
    {
        $captions = [];

        foreach (array_filter(explode("\n\n", $subtitles)) as $subtitle) {
            $entity = new Caption();
            $reflection = new \ReflectionObject($entity);
            $parts = explode("\n", $subtitle);
            $times = explode(' --> ', $parts[1]);

            $index = $reflection->getProperty('index');
            $index->setAccessible(true);
            $index->setValue($entity, (int) $parts[0]);

            $start = $reflection->getProperty('start');
            $start->setAccessible(true);
            $start->setValue($entity, \DateTime::createFromFormat('H:i:s,u', $times[0]));

            $end = $reflection->getProperty('end');
            $end->setAccessible(true);
            $end->setValue($entity, \DateTime::createFromFormat('H:i:s,u', $times[1]));

            $caption = $reflection->getProperty('caption');
            $caption->setAccessible(true);
            $caption->setValue($entity, $parts[2]);

            $captions[] = $entity;
        }

        return $captions;
    }
}
