<?php
namespace YoutubeDl\Entity;

class Video
{
    /**
     * @var \DateTime
     */
    protected $uploadDate;
    /**
     * @var string
     */
    protected $extractor;
    /**
     * @var string
     */
    protected $formatNote;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var int
     */
    protected $likeCount;
    /**
     * @var int
     */
    protected $duration;
    /**
     * @var string
     */
    protected $fulltitle;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var int
     */
    protected $viewCount;
    /**
     * @var string
     */
    protected $playlist;
    /**
     * @var array
     */
    protected $httpHeaders;
    /**
     * @var string
     */
    protected $container;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var int
     */
    protected $playlistIndex;
    /**
     * @var int
     */
    protected $dislikeCount;
    /**
     * @var float
     */
    protected $averageRating;
    /**
     * @var int
     */
    protected $abr;
    /**
     * @var Subtitles[]
     */
    protected $subtitles;
    /**
     * @var int
     */
    protected $fps;
    /**
     * @var int
     */
    protected $ageLimit;
    /**
     * @var string
     */
    protected $webpageUrlBasename;
    /**
     * @var int
     */
    protected $filesize;
    /**
     * @var string
     */
    protected $displayId;
    /**
     * @var int
     */
    protected $asr;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $format;
    /**
     * @var int
     */
    protected $tbr;
    /**
     * @var string
     */
    protected $playlistId;
    /**
     * @var string
     */
    protected $uploader;
    /**
     * @var string
     */
    protected $formatId;
    /**
     * @var string
     */
    protected $uploaderId;
    /**
     * @var Category[]
     */
    protected $categories;
    /**
     * @var string
     */
    protected $playlistTitle;
    /**
     * @var string
     */
    protected $stitle;
    /**
     * @var Thumbnail[]
     */
    protected $thumbnails;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $extractorKey;
    /**
     * @var string
     */
    protected $vcodec;
    /**
     * @var \SimpleXMLElement
     */
    protected $annotations;
    /**
     * @var string
     */
    protected $ext;
    /**
     * @var string
     */
    protected $webpageUrl;
    /**
     * @var Format[]
     */
    protected $formats;
    /**
     * @var string
     */
    protected $acodec;
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $nEntries;
    /**
     * @var int
     */
    protected $preference;
    /**
     * @var \SplFileInfo
     */
    protected $file;
    /**
     * @var int
     */
    protected $commentCount;

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @return string
     */
    public function getExtractor()
    {
        return $this->extractor;
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->formatNote;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getFulltitle()
    {
        return $this->fulltitle;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @return string
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getPlaylistIndex()
    {
        return $this->playlistIndex;
    }

    /**
     * @return int
     */
    public function getDislikeCount()
    {
        return $this->dislikeCount;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * @return Subtitles[]
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @return int
     */
    public function getAgeLimit()
    {
        return $this->ageLimit;
    }

    /**
     * @return string
     */
    public function getWebpageUrlBasename()
    {
        return $this->webpageUrlBasename;
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * @return string
     */
    public function getDisplayId()
    {
        return $this->displayId;
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->asr;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->tbr;
    }

    /**
     * @return string
     */
    public function getPlaylistId()
    {
        return $this->playlistId;
    }

    /**
     * @return string
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->formatId;
    }

    /**
     * @return string
     */
    public function getUploaderId()
    {
        return $this->uploaderId;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getPlaylistTitle()
    {
        return $this->playlistTitle;
    }

    /**
     * @return string
     */
    public function getStitle()
    {
        return $this->stitle;
    }

    /**
     * @return Thumbnail[]
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getExtractorKey()
    {
        return $this->extractorKey;
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->vcodec;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getWebpageUrl()
    {
        return $this->webpageUrl;
    }

    /**
     * @return Format[]
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->acodec;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getNEntries()
    {
        return $this->nEntries;
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }
}
