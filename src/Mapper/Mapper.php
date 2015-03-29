<?php
namespace YoutubeDl\Mapper;

use YoutubeDl\Entity\Video;

class Mapper
{
    protected $mappers = [
        'upload_date' => 'UploadDateMapper',
        'annotations' => 'AnnotationsMapper',
        'formats' => 'FormatsMapper',
        'thumbnails' => 'ThumbnailsMapper',
        'categories' => 'CategoriesMapper',
        'subtitles' => 'SubtitlesMapper',
    ];
    /**
     * @var string
     */
    protected $workingDirectory;
    /**
     * Constructor
     *
     * @param string $workingDirectory
     */
    public function __construct($workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }
    /**
     * Map data to Video object and return it
     *
     * @param array $data
     *
     * @return Video
     * @throws \Exception
     */
    public function map(array $data)
    {
        $video = new Video();

        $reflection = new \ReflectionObject($video);

        $mappers = array_keys($this->mappers);

        foreach ($data as $key => $value) {
            try {
                $prop = $reflection->getProperty($this->snakeToCamel($key));
                $prop->setAccessible(true);
            } catch (\ReflectionException $e) {
                continue;
            }

            if (in_array($key, $mappers)) {
                $mapper = __NAMESPACE__ . '\\' . $this->mappers[$key];

                if (!in_array(__NAMESPACE__ . '\MapperInterface', class_implements($mapper))) {
                    throw new \Exception($mapper . ' must implement mapper interface.');
                }
                /**
                 * @var MapperInterface $mapperObj
                 */
                $mapperObj = new $mapper();

                $prop->setValue($video, $mapperObj->map($value));
            } else {
                $prop->setValue($video, $value);
            }
        }

        $file = $reflection->getProperty('file');
        $file->setAccessible(true);
        $file->setValue($video, new \SplFileInfo(rtrim($this->workingDirectory, '/') . '/' . $video->getFilename()));

        return $video;
    }

    protected function snakeToCamel($value)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
    }
}
