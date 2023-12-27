<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;
use SimpleXMLElement;
use SplFileInfo;

use function libxml_clear_errors;
use function libxml_use_internal_errors;

class Video extends AbstractEntity
{
    protected static array $objectMap = [
        'categories' => Category::class,
        'comments' => Comment::class,
        'formats' => Format::class,
        'requested_formats' => Format::class,
        'requested_subtitles' => Subtitles::class,
        'subtitles' => Subtitles::class,
        'automatic_captions' => Subtitles::class,
        'thumbnails' => Thumbnail::class,
    ];

    public function getError(): ?string
    {
        return $this->get('error');
    }

    public function getId(): ?string
    {
        return $this->get('id');
    }

    public function getTitle(): ?string
    {
        return $this->get('title');
    }

    public function getUrl(): ?string
    {
        return $this->get('url');
    }

    public function getExt(): ?string
    {
        return $this->get('ext');
    }

    public function getAltTitle(): ?string
    {
        return $this->get('alt_title');
    }

    public function getDisplayId(): ?string
    {
        return $this->get('display_id');
    }

    public function getUploader(): ?string
    {
        return $this->get('uploader');
    }

    public function getUploaderUrl(): ?string
    {
        return $this->get('uploader_url');
    }

    public function getLicense(): ?string
    {
        return $this->get('license');
    }

    public function getCreator(): ?string
    {
        return $this->get('creator');
    }

    public function getReleaseDate(): ?DateTimeInterface
    {
        return $this->get('release_date');
    }

    public function getTimestamp(): ?int
    {
        return $this->get('timestamp');
    }

    public function getUploadDate(): ?DateTimeInterface
    {
        return $this->get('upload_date');
    }

    public function getUploaderId(): ?string
    {
        return $this->get('uploader_id');
    }

    public function getChannel(): ?string
    {
        return $this->get('channel');
    }

    public function getChannelId(): ?string
    {
        return $this->get('channel_id');
    }

    public function getChannelUrl(): ?string
    {
        return $this->get('channel_url');
    }

    public function getChannelFollowerCount(): ?int
    {
        return $this->get('channel_follower_count');
    }

    public function getLocation(): ?string
    {
        return $this->get('location');
    }

    public function getDuration(): ?float
    {
        return $this->get('duration');
    }

    public function getViewCount(): ?int
    {
        return $this->get('view_count');
    }

    public function getLikeCount(): ?int
    {
        return $this->get('like_count');
    }

    public function getDislikeCount(): ?int
    {
        return $this->get('dislike_count');
    }

    public function getRepostCount(): ?int
    {
        return $this->get('repost_count');
    }

    public function getAverageRating(): ?float
    {
        return $this->get('average_rating');
    }

    public function getCommentCount(): ?int
    {
        return $this->get('comment_count');
    }

    public function getAgeLimit(): ?int
    {
        return $this->get('age_limit');
    }

    public function getIsLive(): bool
    {
        return $this->get('is_live', false);
    }

    public function getStartTime(): ?int
    {
        return $this->get('start_time');
    }

    public function getEndTime(): ?int
    {
        return $this->get('end_time');
    }

    public function getFormat(): ?string
    {
        return $this->get('format');
    }

    public function getFormatId(): ?string
    {
        return $this->get('format_id');
    }

    public function getFormatNote(): ?string
    {
        return $this->get('format_note');
    }

    public function getWidth(): ?int
    {
        return $this->get('width');
    }

    public function getHeight(): ?int
    {
        return $this->get('height');
    }

    public function getResolution(): ?string
    {
        return $this->get('resolution');
    }

    public function getTbr(): ?float
    {
        return $this->get('tbr');
    }

    public function getAbr(): ?int
    {
        return $this->get('abr');
    }

    public function getAcodec(): ?string
    {
        return $this->get('acodec');
    }

    public function getAsr(): ?int
    {
        return $this->get('asr');
    }

    public function getVbr(): ?string
    {
        return $this->get('vbr');
    }

    public function getFps(): ?float
    {
        return $this->get('fps');
    }

    public function getVcodec(): ?string
    {
        return $this->get('vcodec');
    }

    public function getContainer(): ?string
    {
        return $this->get('container');
    }

    public function getFilesize(): ?int
    {
        return $this->get('filesize');
    }

    public function getFilesizeApprox(): ?int
    {
        return $this->get('filesize_approx');
    }

    public function getProtocol(): ?string
    {
        return $this->get('protocol');
    }

    public function getExtractor(): ?string
    {
        return $this->get('extractor');
    }

    public function getExtractorKey(): ?string
    {
        return $this->get('extractor_key');
    }

    public function getEpoch(): ?int
    {
        return $this->get('epoch');
    }

    public function getAutoNumber(): ?int
    {
        return $this->get('autonumber');
    }

    public function getPlaylist(): ?string
    {
        return $this->get('playlist');
    }

    public function getPlaylistIndex(): ?int
    {
        return $this->get('playlist_index');
    }

    public function getPlaylistId(): ?string
    {
        return $this->get('playlist_id');
    }

    public function getPlaylistTitle(): ?string
    {
        return $this->get('playlist_title');
    }

    public function getPlaylistUploader(): ?string
    {
        return $this->get('playlist_uploader');
    }

    public function getPlaylistUploaderId(): ?string
    {
        return $this->get('playlist_uploader_id');
    }

    public function getChapter(): ?string
    {
        return $this->get('chapter');
    }

    public function getChapterNumber(): ?int
    {
        return $this->get('chapter_number');
    }

    public function getChapterId(): ?string
    {
        return $this->get('chapter_id');
    }

    public function getSeries(): ?string
    {
        return $this->get('series');
    }

    public function getSeason(): ?string
    {
        return $this->get('season');
    }

    public function getSeasonNumber(): ?int
    {
        return $this->get('season_number');
    }

    public function getSeasonId(): ?string
    {
        return $this->get('season_id');
    }

    public function getEpisode(): ?string
    {
        return $this->get('episode');
    }

    public function getEpisodeNumber(): ?int
    {
        return $this->get('episode_number');
    }

    public function getEpisodeId(): ?string
    {
        return $this->get('episode_id');
    }

    public function getTrack(): ?string
    {
        return $this->get('track');
    }

    public function getTrackNumber(): ?int
    {
        return $this->get('track_number');
    }

    public function getTrackId(): ?string
    {
        return $this->get('track_id');
    }

    public function getArtist(): ?string
    {
        return $this->get('artist');
    }

    public function getGenre(): ?string
    {
        return $this->get('genre');
    }

    public function getAlbum(): ?string
    {
        return $this->get('album');
    }

    public function getAlbumType(): ?string
    {
        return $this->get('album_type');
    }

    public function getAlbumArtist(): ?string
    {
        return $this->get('album_artist');
    }

    public function getDiscNumber(): ?int
    {
        return $this->get('disc_number');
    }

    public function getReleaseYear(): ?string
    {
        return $this->get('release_year');
    }

    /**
     * @return array<string, string>
     */
    public function getHttpHeaders(): array
    {
        return $this->get('http_headers', []);
    }

    public function getFilename(): ?string
    {
        return $this->get('_filename');
    }

    /**
     * @return list<Subtitles>
     */
    public function getSubtitles(): array
    {
        return $this->get('subtitles', []);
    }

    /**
     * @return list<Subtitles>
     */
    public function getRequestedSubtitles(): array
    {
        return $this->get('requested_subtitles', []);
    }

    /**
     * @return list<Subtitles>
     */
    public function getAutomaticCaptions(): array
    {
        return $this->get('automatic_captions', []);
    }

    public function getWebpageUrlBasename(): ?string
    {
        return $this->get('webpage_url_basename');
    }

    public function getDescription(): ?string
    {
        return $this->get('description');
    }

    public function getStretchedRatio(): ?float
    {
        return $this->get('stretched_ratio');
    }

    /**
     * @return list<Category>
     */
    public function getCategories(): array
    {
        return $this->get('categories', []);
    }

    /**
     * @return list<Thumbnail>
     */
    public function getThumbnails(): array
    {
        return $this->get('thumbnails', []);
    }

    public function getAnnotations(): ?SimpleXMLElement
    {
        return $this->get('annotations');
    }

    public function getWebpageUrl(): ?string
    {
        return $this->get('webpage_url');
    }

    /**
     * @return list<Format>
     */
    public function getFormats(): array
    {
        return $this->get('formats', []);
    }

    /**
     * @return list<Format>
     */
    public function getRequestedFormats(): array
    {
        return $this->get('requested_formats', []);
    }

    public function getNEntries(): ?int
    {
        return $this->get('n_entries');
    }

    public function getPreference(): ?int
    {
        return $this->get('preference');
    }

    public function getFile(): SplFileInfo
    {
        return $this->get('file');
    }

    public function getMetadataFile(): SplFileInfo
    {
        return $this->get('metadataFile');
    }

    /**
     * @return list<Comment>
     */
    public function getComments(): array
    {
        return $this->get('comments', []);
    }

    /**
     * @return list<string>
     */
    public function getTags(): array
    {
        return $this->get('tags', []);
    }

    public function toJson(int $options = JSON_THROW_ON_ERROR): string
    {
        $data = $this->toArray();
        unset($data['file']);
        unset($data['metadataFile']);

        $json = json_encode($data, $options);

        if ($json === false) {
            throw new JsonException(json_last_error_msg());
        }

        return $json;
    }

    protected function convert(array $data): array
    {
        $data = parent::convert($data);

        if (($data['release_date'] ?? null) !== null) {
            $data['release_date'] = DateTimeImmutable::createFromFormat('!Ymd', $data['release_date']);
        }

        if (($data['upload_date'] ?? null) !== null) {
            $data['upload_date'] = DateTimeImmutable::createFromFormat('!Ymd', $data['upload_date']);
        }

        if (!empty($data['annotations'])) {
            $data['annotations'] = $this->convertAnnotations($data['annotations']);
        }

        return $data;
    }

    private function convertAnnotations(string $data): ?SimpleXMLElement
    {
        try {
            libxml_use_internal_errors(true);

            $obj = new SimpleXMLElement($data);
            libxml_clear_errors();

            return $obj;
        } catch (Exception $e) {
            // If for some reason annotations can't be mapped then just ignore this
        }

        return null;
    }
}
