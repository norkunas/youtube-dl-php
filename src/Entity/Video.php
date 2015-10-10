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
     * @var string
     */
    protected $vbr;

    /**
     * @var string
     */
    protected $resolution;

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
    protected $altTitle;

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
    protected $subtitles = [];

    /**
     * @var Subtitles[]
     */
    protected $automaticCaptions;

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
    protected $stretchedRatio;

    /**
     * @var string
     */
    protected $uploaderId;

    /**
     * @var Category[]
     */
    protected $categories = [];

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
    protected $thumbnails = [];

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
    protected $formats = [];

    /**
     * @var Format[]
     */
    protected $requestedFormats = [];

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
     * @var array
     */
    protected $comments;

    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var bool
     */
    protected $isLive;

    /**
     * @var int
     */
    protected $startTime;

    /**
     * @var int
     */
    protected $endTime;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $creator;

    /**
     * @param \DateTime $uploadDate
     */
    public function setUploadDate(\DateTime $uploadDate)
    {
        $this->uploadDate = $uploadDate;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @param string $extractor
     */
    public function setExtractor($extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @return string
     */
    public function getExtractor()
    {
        return $this->extractor;
    }

    /**
     * @param string $formatNote
     */
    public function setFormatNote($formatNote)
    {
        $this->formatNote = $formatNote;
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->formatNote;
    }

    /**
     * @param string $vbr
     */
    public function setVbr($vbr)
    {
        $this->vbr = $vbr;
    }

    /**
     * @return string
     */
    public function getVbr()
    {
        return $this->vbr;
    }

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $likeCount
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;
    }

    /**
     * @return int
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $fulltitle
     */
    public function setFulltitle($fulltitle)
    {
        $this->fulltitle = $fulltitle;
    }

    /**
     * @return string
     */
    public function getFulltitle()
    {
        return $this->fulltitle;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param string $playlist
     */
    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
    }

    /**
     * @return string
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @param array $httpHeaders
     */
    public function setHttpHeaders($httpHeaders)
    {
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * @param string $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $altTitle
     */
    public function setAltTitle($altTitle)
    {
        $this->altTitle = $altTitle;
    }

    /**
     * @return string
     */
    public function getAltTitle()
    {
        return $this->altTitle;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param int $playlistIndex
     */
    public function setPlaylistIndex($playlistIndex)
    {
        $this->playlistIndex = $playlistIndex;
    }

    /**
     * @return int
     */
    public function getPlaylistIndex()
    {
        return $this->playlistIndex;
    }

    /**
     * @param int $dislikeCount
     */
    public function setDislikeCount($dislikeCount)
    {
        $this->dislikeCount = $dislikeCount;
    }

    /**
     * @return int
     */
    public function getDislikeCount()
    {
        return $this->dislikeCount;
    }

    /**
     * @param float $averageRating
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param int $abr
     */
    public function setAbr($abr)
    {
        $this->abr = $abr;
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * @param Subtitles[] $subtitles
     */
    public function setSubtitles(array $subtitles)
    {
        $this->subtitles = $subtitles;
    }

    /**
     * @return Subtitles[]
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * @param array $automaticCaptions
     */
    public function setAutomaticCaptions(array $automaticCaptions)
    {
        $this->automaticCaptions = $automaticCaptions;
    }

    /**
     * @return Subtitles[]
     */
    public function getAutomaticCaptions()
    {
        return $this->automaticCaptions;
    }

    /**
     * @param int $fps
     */
    public function setFps($fps)
    {
        $this->fps = $fps;
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->fps;
    }

    /**
     * @param int $ageLimit
     */
    public function setAgeLimit($ageLimit)
    {
        $this->ageLimit = $ageLimit;
    }

    /**
     * @return int
     */
    public function getAgeLimit()
    {
        return $this->ageLimit;
    }

    /**
     * @param string $webpageUrlBasename
     */
    public function setWebpageUrlBasename($webpageUrlBasename)
    {
        $this->webpageUrlBasename = $webpageUrlBasename;
    }

    /**
     * @return string
     */
    public function getWebpageUrlBasename()
    {
        return $this->webpageUrlBasename;
    }

    /**
     * @param int $filesize
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * @param string $displayId
     */
    public function setDisplayId($displayId)
    {
        $this->displayId = $displayId;
    }

    /**
     * @return string
     */
    public function getDisplayId()
    {
        return $this->displayId;
    }

    /**
     * @param int $asr
     */
    public function setAsr($asr)
    {
        $this->asr = $asr;
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->asr;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param int $tbr
     */
    public function setTbr($tbr)
    {
        $this->tbr = $tbr;
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->tbr;
    }

    /**
     * @param string $playlistId
     */
    public function setPlaylistId($playlistId)
    {
        $this->playlistId = $playlistId;
    }

    /**
     * @return string
     */
    public function getPlaylistId()
    {
        return $this->playlistId;
    }

    /**
     * @param string $uploader
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @return string
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @param string $formatId
     */
    public function setFormatId($formatId)
    {
        $this->formatId = $formatId;
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->formatId;
    }

    /**
     * @param string $stretchedRatio
     */
    public function setStretchedRatio($stretchedRatio)
    {
        $this->stretchedRatio = $stretchedRatio;
    }

    /**
     * @return string
     */
    public function getStretchedRatio()
    {
        return $this->stretchedRatio;
    }

    /**
     * @param string $uploaderId
     */
    public function setUploaderId($uploaderId)
    {
        $this->uploaderId = $uploaderId;
    }

    /**
     * @return string
     */
    public function getUploaderId()
    {
        return $this->uploaderId;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string $playlistTitle
     */
    public function setPlaylistTitle($playlistTitle)
    {
        $this->playlistTitle = $playlistTitle;
    }

    /**
     * @return string
     */
    public function getPlaylistTitle()
    {
        return $this->playlistTitle;
    }

    /**
     * @param string $stitle
     */
    public function setStitle($stitle)
    {
        $this->stitle = $stitle;
    }

    /**
     * @return string
     */
    public function getStitle()
    {
        return $this->stitle;
    }

    /**
     * @param Thumbnail[] $thumbnails
     */
    public function setThumbnails(array $thumbnails)
    {
        $this->thumbnails = $thumbnails;
    }

    /**
     * @return Thumbnail[]
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $extractorKey
     */
    public function setExtractorKey($extractorKey)
    {
        $this->extractorKey = $extractorKey;
    }

    /**
     * @return string
     */
    public function getExtractorKey()
    {
        return $this->extractorKey;
    }

    /**
     * @param string $vcodec
     */
    public function setVcodec($vcodec)
    {
        $this->vcodec = $vcodec;
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->vcodec;
    }

    /**
     * @param \SimpleXMLElement $annotations
     */
    public function setAnnotations($annotations)
    {
        $this->annotations = $annotations;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param string $webpageUrl
     */
    public function setWebpageUrl($webpageUrl)
    {
        $this->webpageUrl = $webpageUrl;
    }

    /**
     * @return string
     */
    public function getWebpageUrl()
    {
        return $this->webpageUrl;
    }

    /**
     * @param Format[] $formats
     */
    public function setFormats(array $formats)
    {
        $this->formats = $formats;
    }

    /**
     * @return Format[]
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @param array $requestedFormats
     */
    public function setRequestedFormats(array $requestedFormats)
    {
        $this->requestedFormats = $requestedFormats;
    }

    /**
     * @return Format[]
     */
    public function getRequestedFormats()
    {
        return $this->requestedFormats;
    }

    /**
     * @param string $acodec
     */
    public function setAcodec($acodec)
    {
        $this->acodec = $acodec;
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->acodec;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $nEntries
     */
    public function setNEntries($nEntries)
    {
        $this->nEntries = $nEntries;
    }

    /**
     * @return int
     */
    public function getNEntries()
    {
        return $this->nEntries;
    }

    /**
     * @param int $preference
     */
    public function setPreference($preference)
    {
        $this->preference = $preference;
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param \SplFileInfo $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param int $commentCount
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param bool $isLive
     */
    public function setIsLive($isLive)
    {
        $this->isLive = $isLive;
    }

    /**
     * @return bool
     */
    public function getIsLive()
    {
        return $this->isLive;
    }

    /**
     * @param int $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return int
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param int $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return int
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
