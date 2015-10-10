<?php

namespace YoutubeDl\Mapper;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use YoutubeDl\Entity\Video;

class Mapper
{
    protected $mappers = [
        'UploadDateMapper' => ['upload_date'],
        'AnnotationsMapper' => ['annotations'],
        'FormatsMapper' => ['formats', 'requested_formats'],
        'ThumbnailsMapper' => ['thumbnails'],
        'CategoriesMapper' => ['categories'],
        'SubtitlesMapper' => ['subtitles'],
        'CommentsMapper' => ['comments'],
    ];

    protected $ignore = [
        'thumbnail',
    ];

    /**
     * @var string
     */
    protected $downloadPath;

    /**
     * @var PropertyAccessorInterface
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
    {
        $video = new Video();

        $data['file'] = new \SplFileInfo(rtrim($this->downloadPath, '/') . '/' . $data['_filename']);

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

        return $video;
    }

    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return MapperInterface|bool
     */
    protected function findInnerMapper($key)
    {
        foreach ($this->mappers as $class => $mapperProps) {
            if (in_array($key, $mapperProps)) {
                $mapperClass = __NAMESPACE__ . '\\' . $class;

                break;
            }
        }

        if (!isset($mapperClass)) {
            return false;
        }

        if (!in_array(__NAMESPACE__ . '\\MapperInterface', class_implements($mapperClass))) {
            throw new \Exception($mapperClass . ' must implement mapper interface.');
        }

        $mapperObj = new $mapperClass();

        if ($mapperObj instanceof PropertyAccessorAwareMapper) {
            $mapperObj->setPropertyAccessor($this->propertyAccessor);
        }

        return $mapperObj;
    }
}
