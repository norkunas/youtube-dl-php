<?php
namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Caption;
use YoutubeDl\Entity\Category;
use YoutubeDl\Entity\Format;
use YoutubeDl\Entity\Subtitles;
use YoutubeDl\Entity\Thumbnail;
use YoutubeDl\Entity\Video;

class Mapper
{
    protected $skipKeys = ['http_headers'];
    /**
     * Map data to Video object and return it
     *
     * @param array $data
     *
     * @return Video
     */
    public function map(array $data)
    {
        $video = new Video();

        $reflection = new \ReflectionObject($video);

        foreach ($data as $key => $value) {
            if (in_array($key, $this->skipKeys)) {
                continue;
            }

            $prop = $this->getProperty($reflection, $key);

            if (false === $prop) {
                continue;
            }

            switch ($key) {
                case 'upload_date':
                    $prop->setValue($video, $this->mapUploadDate($value));

                    break;
                case 'annotations':
                    $prop->setValue($video, $this->mapAnnotations($value));

                    break;
                case 'formats':
                    $prop->setValue($video, $this->mapChildObjects(new Format, $value));

                    break;
                case 'thumbnails':
                    $prop->setValue($video, $this->mapChildObjects(new Thumbnail, $value, ['id', 'url']));

                    break;
                case 'categories':
                    $prop->setValue($video, $this->mapChildObjects(new Category, $value, ['title']));

                    break;
                case 'subtitles':
                    $prop->setValue($video, $this->mapSubtitles(new Subtitles, new Caption(), $value));

                    break;
                default:
                    $prop->setValue($video, $value);
            }
        }

        return $video;
    }

    protected function mapChildObjects($object, $values, $props = []) {
        $data = [];

        foreach ($values as $child) {
            if (is_array($child)) {
                $obj = clone $object;
                $reflection = new \ReflectionObject($obj);

                foreach ($child as $key => $value) {
                    $this->setPropertyValue($obj, $reflection, $key, $value);
                }

                $data[] = $obj;
            } else {
                $obj = clone $object;
                $reflection = new \ReflectionObject($obj);

                foreach ($props as $key) {
                    $this->setPropertyValue($obj, $reflection, $key, $child);
                }

                $data[] = $obj;
            }
        }

        return $data;
    }

    protected function mapUploadDate($value)
    {
        if ($date = \DateTime::createFromFormat('Ymd', $value)) {
            return $date;
        }

        return null;
    }

    protected function mapAnnotations($xml)
    {
        try {
            libxml_use_internal_errors(true);

            $data = new \SimpleXMLElement($xml);
            libxml_clear_errors();

            if ($data) {
                return $data;
            }
        } catch (\Exception $e) { }

        return null;
    }

    protected function mapSubtitles($subsObject, $captionObject, $values)
    {
        if (!is_array($values)) {
            return null;
        }

        $data = [];

        foreach ($values as $lang => $subtitles) {
            $subs = clone $subsObject;
            $reflection = new \ReflectionObject($subs);
            $this->setPropertyValue($subs, $reflection, 'locale', $lang);

            $captions = [];

            foreach (array_filter(explode("\n\n", $subtitles)) as $subtitle) {
                $caption = clone $captionObject;
                $captionReflection = new \ReflectionObject($caption);
                $parts = explode("\n", $subtitle);
                $times = explode(' --> ', $parts[1]);
                $this->setPropertyValue($caption, $captionReflection, 'index', (int) $parts[0]);
                $this->setPropertyValue($caption, $captionReflection, 'start', \DateTime::createFromFormat('H:i:s,u', $times[0]));
                $this->setPropertyValue($caption, $captionReflection, 'end', \DateTime::createFromFormat('H:i:s,u', $times[1]));
                $this->setPropertyValue($caption, $captionReflection, 'caption', $parts[2]);

                $captions[] = $caption;
            }

            $this->setPropertyValue($subs, $reflection, 'captions', $captions);

            $data[] = $subs;
        }

        return $data;
    }

    protected function getProperty(\ReflectionObject $obj, $key)
    {
        $key = $this->snakeToCamel($key);

        if (!$obj->hasProperty($key)) {
            return false;
        }

        $property = $obj->getProperty($key);
        $property->setAccessible(true);

        return $property;
    }

    protected function setPropertyValue($object, \ReflectionObject $reflection, $key, $value)
    {
        $property = $this->getProperty($reflection, $key);

        if (false === $property) {
            return;
        }

        $property->setValue($object, $value);
    }

    protected function snakeToCamel($value)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
    }
}
