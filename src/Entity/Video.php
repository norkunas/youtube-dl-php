<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

class Video extends AbstractEntity
{
    protected static $objectMap = [
        'comments' => 'YoutubeDl\\Entity\\Comment',
        'formats' => 'YoutubeDl\\Entity\\Format',
        'requested_formats' => 'YoutubeDl\\Entity\\Format',
        'requested_subtitles' => 'YoutubeDl\\Entity\Subtitles',
        'subtitles' => 'YoutubeDl\\Entity\\Subtitles',
        'thumbnails' => 'YoutubeDl\\Entity\Thumbnail',
    ];

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->get('upload_date');
    }

    /**
     * @return string
     */
    public function getExtractor()
    {
        return $this->get('extractor');
    }

    /**
     * @return string
     */
    public function getFormatNote()
    {
        return $this->get('format_note');
    }

    /**
     * @return string
     */
    public function getVbr()
    {
        return $this->get('vbr');
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->get('resolution');
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->get('height');
    }

    /**
     * @return int
     */
    public function getLikeCount()
    {
        return $this->get('like_count');
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->get('duration');
    }

    /**
     * @return string
     */
    public function getFulltitle()
    {
        return $this->get('fulltitle');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->get('view_count');
    }

    /**
     * @return string
     */
    public function getPlaylist()
    {
        return $this->get('playlist');
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->get('http_headers', []);
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->get('container');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * @return string
     */
    public function getAltTitle()
    {
        return $this->get('alt_title');
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->get('_filename');
    }

    /**
     * @return int
     */
    public function getPlaylistIndex()
    {
        return $this->get('playlist_index');
    }

    /**
     * @return int
     */
    public function getDislikeCount()
    {
        return $this->get('dislike_count');
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->get('average_rating');
    }

    /**
     * @return int
     */
    public function getAbr()
    {
        return $this->get('abr');
    }

    /**
     * @return Subtitles[]
     */
    public function getSubtitles()
    {
        return $this->get('subtitles');
    }

    /**
     * @return Subtitles[]
     */
    public function getRequestedSubtitles()
    {
        return $this->get('requested_subtitles');
    }

    /**
     * @return Subtitles[]
     */
    public function getAutomaticCaptions()
    {
        return $this->get('automatic_captions');
    }

    /**
     * @return int
     */
    public function getFps()
    {
        return $this->get('fps');
    }

    /**
     * @return int
     */
    public function getAgeLimit()
    {
        return $this->get('age_limit');
    }

    /**
     * @return string
     */
    public function getWebpageUrlBasename()
    {
        return $this->get('webpage_url_basename');
    }

    /**
     * @return int
     */
    public function getFilesize()
    {
        return $this->get('filesize');
    }

    /**
     * @return string
     */
    public function getDisplayId()
    {
        return $this->get('display_id');
    }

    /**
     * @return int
     */
    public function getAsr()
    {
        return $this->get('asr');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->get('format');
    }

    /**
     * @return int
     */
    public function getTbr()
    {
        return $this->get('tbr');
    }

    /**
     * @return string
     */
    public function getPlaylistId()
    {
        return $this->get('playlist_id');
    }

    /**
     * @return string
     */
    public function getUploader()
    {
        return $this->get('uploader');
    }

    /**
     * @return string
     */
    public function getFormatId()
    {
        return $this->get('format_id');
    }

    /**
     * @return float
     */
    public function getStretchedRatio()
    {
        return $this->get('stretched_ratio');
    }

    /**
     * @return string
     */
    public function getUploaderId()
    {
        return $this->get('uploader_id');
    }

    /**
     * @return Category[]|string
     */
    public function getCategories()
    {
        $categories = [];

        foreach ($this->get('categories', []) as $title) {
            $categories[] = new Category(['title' => $title]); // BC
        }

        return $categories;
    }

    /**
     * @return string
     */
    public function getPlaylistTitle()
    {
        return $this->get('playlist_title');
    }

    /**
     * @return string
     */
    public function getStitle()
    {
        return $this->get('stitle');
    }

    /**
     * @return Thumbnail[]
     */
    public function getThumbnails()
    {
        return $this->get('thumbnails');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * @return string
     */
    public function getExtractorKey()
    {
        return $this->get('extractor_key');
    }

    /**
     * @return string
     */
    public function getVcodec()
    {
        return $this->get('vcodec');
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getAnnotations()
    {
        return $this->get('annotations');
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->get('ext');
    }

    /**
     * @return string
     */
    public function getWebpageUrl()
    {
        return $this->get('webpage_url');
    }

    /**
     * @return Format[]
     */
    public function getFormats()
    {
        return $this->get('formats');
    }

    /**
     * @return Format[]
     */
    public function getRequestedFormats()
    {
        return $this->get('requested_formats');
    }

    /**
     * @return string
     */
    public function getAcodec()
    {
        return $this->get('acodec');
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->get('width');
    }

    /**
     * @return int
     */
    public function getNEntries()
    {
        return $this->get('n_entries');
    }

    /**
     * @return int
     */
    public function getPreference()
    {
        return $this->get('preference');
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->get('file');
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        return $this->get('comment_count');
    }

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->get('comments', []);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->get('tags', []);
    }

    /**
     * @return bool
     */
    public function getIsLive()
    {
        return $this->get('is_live');
    }

    /**
     * @return int
     */
    public function getStartTime()
    {
        return $this->get('start_time');
    }

    /**
     * @return int
     */
    public function getEndTime()
    {
        return $this->get('end_time');
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->get('location');
    }

    /**
     * @return string
     */
    public function getCreator()
    {
        return $this->get('creator');
    }

    protected function convert(array $data): array
    {
        $data = parent::convert($data);

        if (!empty($data['annotations'])) {
            $data['annotations'] = $this->convertAnnotations($data['annotations']);
        }

        if (!empty($data['upload_date']) && $date = \DateTime::createFromFormat('Ymd', $data['upload_date'])) {
            $data['upload_date'] = $date;
        }

        return $data;
    }

    private function convertAnnotations($data)
    {
        try {
            libxml_use_internal_errors(true);

            $obj = new \SimpleXMLElement($data);
            libxml_clear_errors();

            if ($obj) {
                return $obj;
            }
        } catch (\Exception $e) {
            // If for some reason annotations can't be mapped then just ignore this
        }

        return;
    }
}
