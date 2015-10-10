<?php

namespace YoutubeDl\Mapper;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use YoutubeDl\Entity\Video;

class Mapper
{
    protected $mappers = [
        'upload_date' => 'UploadDateMapper',
        'annotations' => 'AnnotationsMapper',
        'formats' => 'FormatsMapper',
        'requested_formats' => 'FormatsMapper',
        'thumbnails' => 'ThumbnailsMapper',
        'categories' => 'CategoriesMapper',
        'subtitles' => 'SubtitlesMapper',
    ];

    protected $ignore = [
        //'automatic_captions',
        'requested_subtitles',
        'thumbnail',
    ];

    /**
     * @var string
     */
    protected $downloadPath;

    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * Constructor.
     *
     * @param string                    $downloadPath
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct($downloadPath, PropertyAccessorInterface $propertyAccessor)
    {
        $this->downloadPath = $downloadPath;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * Map data to Video object and return it.
     *
     * @param array $data
     *
     * @throws \Exception
     *
     * @return Video
     */
    public function map(array $data)
    {//var_dump($data);exit;
        $video = new Video();

        foreach ($data as $field => $value) {
            if (in_array($field, $this->ignore)) {
                continue;
            }

            if ($mapper = $this->findInnerMapper($field)) {
                $value = $mapper->map($value);
            }

            try {
                $this->propertyAccessor->setValue($video, $field, $value);
            } catch (\Exception $e) {
                // Ignore if property does not exist
            }
        }

        $file = new \SplFileInfo(rtrim($this->downloadPath, '/') . '/' . $video->getFilename());
        $this->propertyAccessor->setValue($video, 'file', $file);

        return $video;
    }

    /**
     * @param string $key
     *
     * @return MapperInterface|bool
     * @throws \Exception
     */
    protected function findInnerMapper($key)
    {
        if (!isset($this->mappers[$key])) {
            return false;
        }

        $mapper = __NAMESPACE__ . '\\' . $this->mappers[$key];

        if (!in_array(__NAMESPACE__ . '\\MapperInterface', class_implements($mapper))) {
            throw new \Exception($mapper . ' must implement mapper interface.');
        }

        $mapperObj = new $mapper();

        if (in_array('YoutubeDl\\Mapper\\PropertyAccessorAwareTrait', class_uses($mapperObj))) {
            /**
             * @var PropertyAccessorAwareTrait $mapperObj
             */
            $mapperObj->setPropertyAccessor($this->propertyAccessor);
        }

        return $mapperObj;
    }
}
