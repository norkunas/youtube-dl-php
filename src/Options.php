<?php

declare(strict_types=1);

namespace YoutubeDl;

use DateTimeInterface;
use YoutubeDl\Exception\InvalidArgumentException;
use function in_array;

class Options
{
    // @deprecated
    public const EXTERNAL_DOWNLOADERS = ['aria2c', 'avconv', 'axel', 'curl', 'ffmpeg', 'httpie', 'wget'];

    public const AUDIO_FORMAT_BEST = 'best';
    public const AUDIO_FORMAT_AAC = 'aac';
    public const AUDIO_FORMAT_FLAC = 'flac';
    public const AUDIO_FORMAT_MP3 = 'mp3';
    public const AUDIO_FORMAT_M4A = 'm4a';
    public const AUDIO_FORMAT_OPUS = 'opus';
    public const AUDIO_FORMAT_VORBIS = 'vorbis';
    public const AUDIO_FORMAT_WAV = 'wav';
    public const AUDIO_FORMAT_ALAC = 'alac';

    // @deprecated
    public const AUDIO_FORMATS = [
        self::AUDIO_FORMAT_BEST,
        self::AUDIO_FORMAT_AAC,
        self::AUDIO_FORMAT_FLAC,
        self::AUDIO_FORMAT_MP3,
        self::AUDIO_FORMAT_M4A,
        self::AUDIO_FORMAT_OPUS,
        self::AUDIO_FORMAT_VORBIS,
        self::AUDIO_FORMAT_WAV,
        self::AUDIO_FORMAT_ALAC,
    ];

    public const RECODE_VIDEO_FORMATS = ['mp4', 'flv', 'ogg', 'webm', 'mkv', 'avi'];

    public const SUBTITLE_FORMAT_SRT = 'srt';
    public const SUBTITLE_FORMAT_VTT = 'vtt';
    public const SUBTITLE_FORMAT_ASS = 'ass';
    public const SUBTITLE_FORMAT_LRC = 'lrc';

    // @deprecated
    public const SUBTITLE_FORMATS = [
        self::SUBTITLE_FORMAT_SRT,
        self::SUBTITLE_FORMAT_VTT,
        self::SUBTITLE_FORMAT_ASS,
        self::SUBTITLE_FORMAT_LRC,
    ];

    public const MERGE_OUTPUT_FORMAT_MKV = 'mkv';
    public const MERGE_OUTPUT_FORMAT_MP4 = 'mp4';
    public const MERGE_OUTPUT_FORMAT_OGG = 'ogg';
    public const MERGE_OUTPUT_FORMAT_WEBM = 'webm';
    public const MERGE_OUTPUT_FORMAT_FLV = 'flv';

    // @deprecated
    public const MERGE_OUTPUT_FORMATS = [
        self::MERGE_OUTPUT_FORMAT_MKV,
        self::MERGE_OUTPUT_FORMAT_MP4,
        self::MERGE_OUTPUT_FORMAT_OGG,
        self::MERGE_OUTPUT_FORMAT_WEBM,
        self::MERGE_OUTPUT_FORMAT_FLV,
    ];

    private ?string $downloadPath = null;
    private bool $cleanupMetadata = true;

    // Network Options
    private ?string $proxy = null;
    private ?int $socketTimeout = null;
    private ?string $sourceAddress = null;
    private bool $forceIpV4 = false;
    private bool $forceIpV6 = false;

    // Geo Restriction
    private ?string $geoVerificationProxy = null;
    private bool $geoBypass = false;
    private bool $noGeoBypass = false;
    private ?string $geoBypassCountry = null;
    private ?string $geoBypassIpBlock = null;

    // Video Selection
    private ?int $playlistStart = null;
    private ?int $playlistEnd = null;
    private array $playlistItems = [];
    private ?string $matchTitle = null;
    private ?string $rejectTitle = null;
    private ?int $maxDownloads = null;
    private ?string $minFileSize = null;
    private ?string $maxFileSize = null;
    private ?string $date = null;
    private ?string $dateBefore = null;
    private ?string $dateAfter = null;
    private ?int $minViews = null;
    private ?int $maxViews = null;
    private ?string $matchFilter = null;
    private bool $noPlaylist = false;
    private bool $yesPlaylist = false;
    private ?int $ageLimit = null;

    // Download Options
    private ?string $limitRate = null;
    private ?string $retries = null;
    private ?string $fragmentRetries = null;
    private bool $skipUnavailableFragments = false;
    private bool $keepFragments = false;
    private ?string $bufferSize = null;
    private bool $noResizeBuffer = false;
    private ?string $httpChunkSize = null;
    private bool $playlistReverse = false;
    private bool $playlistRandom = false;
    private ?string $xattrSetFilesize = null;
    private bool $hlsPreferNative = false;
    private bool $hlsPreferFFmpeg = false;
    private bool $hlsUseMpegts = false;
    private ?string $externalDownloader = null;
    private ?string $externalDownloaderArgs = null;

    // Filesystem Options
    private ?string $batchFile = null;
    private bool $id = false;
    private string $output = '%(title)s-%(id)s.%(ext)s';
    private ?int $autoNumberStart = null;
    private bool $restrictFilenames = false;
    private bool $noOverwrites = false;
    private bool $continue = false;
    private bool $noContinue = false;
    private bool $noPart = false;
    private bool $noMtime = false;
    private bool $writeDescription = false;
    private bool $writeAnnotations = false;
    private ?string $loadInfoJson = null;
    private ?string $cookies = null;
    private ?string $cacheDir = null;
    private bool $noCacheDir = false;
    private bool $rmCacheDir = false;

    // Thumbnail Images Options
    private bool $writeThumbnail = false;
    private bool $writeAllThumbnails = false;

    // Verbosity / Simulation Options
    private bool $skipDownload = false;
    private bool $verbose = false;
    private bool $writePages = false;
    private bool $printTraffic = false;
    private bool $callHome = false;
    private bool $noCallHome = false;

    // Workaround Options
    private ?string $encoding = null;
    private bool $noCheckCertificate = false;
    private bool $preferInsecure = false;
    private ?string $userAgent = null;
    private ?string $referer = null;
    private array $headers = [];
    private ?int $sleepInterval = null;
    private ?int $maxSleepInterval = null;

    // Video Format Options
    private ?string $format = null;
    private bool $youtubeSkipDashManifest = false;
    private ?string $mergeOutputFormat = null;

    // Subtitle Options
    private ?bool $writeSub = false;
    private ?bool $writeAutoSub = false;
    private ?bool $allSubs = false;
    private ?string $subFormat = null;
    private array $subLang = [];

    // Authentication Options
    private ?string $username = null;
    private ?string $password = null;
    private ?string $twoFactor = null;
    private bool $netrc = false;
    private ?string $videoPassword = null;

    // Adobe Pass Options
    private ?string $apMso = null;
    private ?string $apUsername = null;
    private ?string $apPassword = null;

    // Post-processing Options
    private bool $extractAudio = false;
    private ?string $audioFormat = null;
    private ?string $audioQuality = null;
    private ?string $remuxVideo = null;
    private ?string $recodeVideo = null;
    private ?string $postProcessorArgs = null;
    private bool $keepVideo = false;
    private bool $noPostOverwrites = false;
    private bool $embedSubs = false;
    private bool $embedThumbnail = false;
    private bool $addMetadata = false;
    private ?string $metadataFromTitle = null;
    private bool $xattrs = false;
    private bool $preferAvconv = false;
    private bool $preferFFmpeg = false;
    private ?string $ffmpegLocation = null;
    private ?string $exec = null;
    private ?string $convertSubsFormat = null;

    private array $url = [];

    private function __construct()
    {
    }

    /**
     * Configure where to store downloads.
     * Arguments from `output` template also are available.
     */
    public function downloadPath(string $downloadPath): self
    {
        $new = clone $this;
        $new->downloadPath = rtrim($downloadPath, '\/');

        return $new;
    }

    public function getDownloadPath(): ?string
    {
        return $this->downloadPath;
    }

    public function cleanupMetadata(bool $cleanup): self
    {
        $new = clone $this;
        $new->cleanupMetadata = $cleanup;

        return $new;
    }

    public function getCleanupMetadata(): bool
    {
        return $this->cleanupMetadata;
    }

    /**
     * Use the specified HTTP/HTTPS/SOCKS proxy. To enable SOCKS proxy, specify
     * a proper scheme. For example socks5://127.0.0.1:1080/. Pass in an empty
     * string (--proxy "") for direct connection.
     */
    public function proxy(?string $proxy): self
    {
        $new = clone $this;
        $new->proxy = $proxy;

        return $new;
    }

    /**
     * Time to wait before giving up, in seconds.
     */
    public function socketTimeout(?int $socketTimeout): self
    {
        $new = clone $this;
        $new->socketTimeout = $socketTimeout;

        return $new;
    }

    /**
     * Client-side IP address to bind to.
     */
    public function sourceAddress(?string $sourceAddress): self
    {
        $new = clone $this;
        $new->sourceAddress = $sourceAddress;

        return $new;
    }

    /**
     * Make all connections via IPv4.
     */
    public function forceIpV4(): self
    {
        $new = clone $this;
        $new->forceIpV6 = false;
        $new->forceIpV4 = true;

        return $new;
    }

    /**
     * Make all connections via IPv6.
     */
    public function forceIpV6(): self
    {
        $new = clone $this;
        $new->forceIpV4 = false;
        $new->forceIpV6 = true;

        return $new;
    }

    /**
     * Use this proxy to verify the IP address for some geo-restricted sites.
     * The default proxy specified by --proxy (or none, if the option is not
     * present) is used for the actual downloading.
     */
    public function geoVerificationProxy(?string $geoVerificationProxy): self
    {
        $new = clone $this;
        $new->geoVerificationProxy = $geoVerificationProxy;

        return $new;
    }

    /**
     * Bypass geographic restriction via faking X-Forwarded-For HTTP header.
     */
    public function geoByPass(): self
    {
        $new = clone $this;
        $new->geoBypass = true;

        return $new;
    }

    /**
     * Do not bypass geographic restriction via faking X-Forwarded-For HTTP
     * header.
     */
    public function noGeoBypass(): self
    {
        $new = clone $this;
        $new->noGeoBypass = true;

        return $new;
    }

    /**
     * Force bypass geographic restriction with explicitly provided two-letter
     * ISO 3166-2 country code.
     */
    public function geoBypassCountry(?string $code): self
    {
        $new = clone $this;
        $new->geoBypassCountry = $code;

        return $new;
    }

    /**
     * Force bypass geographic restriction with explicitly provided IP block in
     * CIDR notation.
     */
    public function geoBypassIpBlock(?string $ipBlock): self
    {
        $new = clone $this;
        $new->geoBypassIpBlock = $ipBlock;

        return $new;
    }

    /**
     * Playlist video to start at (default is 1).
     */
    public function playlistStart(?int $playlistStart): self
    {
        $new = clone $this;
        $new->playlistStart = $playlistStart;

        return $new;
    }

    /**
     * Playlist video to end at (default is last).
     */
    public function playlistEnd(?int $playlistEnd): self
    {
        $new = clone $this;
        $new->playlistEnd = $playlistEnd;

        return $new;
    }

    /**
     * Playlist video items to download. Specify indices of the videos in the
     * playlist like: [1, 2, 5, 8]. If you want to download videos indexed 1, 2,
     * 5, 8 in the playlist. You can specify range: ['1-3', '7', '10-13'], it
     * will download the videos at index 1, 2, 3, 7, 10, 11, 12 and 13.
     */
    public function playlistItems(array $playlistItems): self
    {
        $new = clone $this;
        $new->playlistItems = $playlistItems;

        return $new;
    }

    /**
     * Download only matching titles (regex or caseless sub-string).
     */
    public function matchTitle(?string $title): self
    {
        $new = clone $this;
        $new->matchTitle = $title;

        return $new;
    }

    /**
     * Skip download for matching titles (regex or caseless sub-string).
     */
    public function rejectTitle(?string $title): self
    {
        $new = clone $this;
        $new->rejectTitle = $title;

        return $new;
    }

    /**
     * Abort after downloading NUMBER files.
     */
    public function maxDownloads(?int $maxDownloads): self
    {
        $new = clone $this;
        $new->maxDownloads = $maxDownloads;

        return $new;
    }

    /**
     * Do not download any videos smaller than `$size` (e.g. 50k or 44.6m).
     */
    public function minFileSize(?string $size): self
    {
        $new = clone $this;
        $new->minFileSize = $size;

        return $new;
    }

    /**
     * Do not download any videos larger than `$size` (e.g. 50k or 44.6m).
     */
    public function maxFileSize(?string $size): self
    {
        $new = clone $this;
        $new->maxFileSize = $size;

        return $new;
    }

    /**
     * Download only videos uploaded in this date.
     */
    public function date(?DateTimeInterface $date): self
    {
        $new = clone $this;
        $new->date = $date !== null ? $date->format('Ymd') : null;

        return $new;
    }

    /**
     * Download only videos uploaded on or before this date (i.e. inclusive).
     */
    public function dateBefore(?DateTimeInterface $before): self
    {
        $new = clone $this;
        $new->dateBefore = $before !== null ? $before->format('Ymd') : null;

        return $new;
    }

    /**
     * Download only videos uploaded on or after this date (i.e. inclusive).
     */
    public function dateAfter(?DateTimeInterface $after): self
    {
        $new = clone $this;
        $new->dateAfter = $after !== null ? $after->format('Ymd') : $after;

        return $new;
    }

    /**
     * Do not download any videos with less than `$count` views.
     */
    public function minViews(?int $count): self
    {
        $new = clone $this;
        $new->minViews = $count;

        return $new;
    }

    /**
     * Do not download any videos with more than `$count` views.
     */
    public function maxViews(?int $count): self
    {
        $new = clone $this;
        $new->maxViews = $count;

        return $new;
    }

    /**
     * Generic video filter. Specify any key (see the "OUTPUT TEMPLATE" for a
     * list of available keys) to match if the key is present, !key to check if
     * the key is not present, key > NUMBER (like "comment_count > 12", also
     * works with >=, <, <=, !=, =) to compare against a number, key = 'LITERAL'
     * (like "uploader = 'Mike Smith'", also works with !=) to match against a
     * string literal and & to require multiple matches. Values which are not
     * known are excluded unless you put a question mark (?) after the operator.
     * For example, to only match videos that have been liked more than 100
     * times and disliked less than 50 times (or the dislike functionality is
     * not available at the given service), but who also have a description, use
     * --match-filter "like_count > 100 & dislike_count <? 50 & description".
     *
     * @see https://github.com/ytdl-org/youtube-dl#output-template
     */
    public function matchFilter(?string $filter): self
    {
        $new = clone $this;
        $new->matchFilter = $filter;

        return $new;
    }

    /**
     * Download only the video, if the URL refers to a video and a playlist.
     */
    public function noPlaylist(): self
    {
        $new = clone $this;
        $new->yesPlaylist = false;
        $new->noPlaylist = true;

        return $new;
    }

    /**
     * Download the playlist, if the URL refers to a video and a playlist.
     */
    public function yesPlaylist(): self
    {
        $new = clone $this;
        $new->noPlaylist = false;
        $new->yesPlaylist = true;

        return $new;
    }

    /**
     * Download only videos suitable for the given age.
     */
    public function ageLimit(?int $ageLimit): self
    {
        $new = clone $this;
        $new->ageLimit = $ageLimit;

        return $new;
    }

    /**
     * Maximum download rate in bytes per second (e.g. 50K or 4.2M).
     */
    public function limitRate(?string $limitRate): self
    {
        $new = clone $this;
        $new->limitRate = $limitRate;

        return $new;
    }

    /**
     * Number of retries (default is 10), or "infinite".
     */
    public function retries(?string $retries): self
    {
        $new = clone $this;
        $new->retries = $retries;

        return $new;
    }

    /**
     * Number of retries for a fragment (default is 10), or "infinite"
     * (DASH, hlsnative and ISM).
     */
    public function fragmentRetries(?string $fragmentRetries): self
    {
        $new = clone $this;
        $new->fragmentRetries = $fragmentRetries;

        return $new;
    }

    /**
     * Skip unavailable fragments (DASH, hlsnative and ISM).
     */
    public function skipUnavailableFragments(bool $skipUnavailableFragments): self
    {
        $new = clone $this;
        $new->skipUnavailableFragments = $skipUnavailableFragments;

        return $new;
    }

    /**
     * Keep downloaded fragments on disk after downloading is finished;
     * fragments are erased by default.
     */
    public function keepFragments(bool $keepFragments): self
    {
        $new = clone $this;
        $new->keepFragments = $keepFragments;

        return $new;
    }

    /**
     * Size of download buffer (e.g. 1024 or 16K) (default is 1024).
     */
    public function bufferSize(?string $bufferSize): self
    {
        $new = clone $this;
        $new->bufferSize = $bufferSize;

        return $new;
    }

    /**
     * Do not automatically adjust the buffer size. By default, the buffer size
     * is automatically resized from an initial value of SIZE.
     */
    public function noResizeBuffer(bool $noResizeBuffer): self
    {
        $new = clone $this;
        $new->noResizeBuffer = $noResizeBuffer;

        return $new;
    }

    /**
     * Size of a chunk for chunk-based HTTP downloading (e.g. 10485760 or 10M)
     * (default is disabled). May be useful for bypassing bandwidth throttling
     * imposed by a webserver (experimental).
     */
    public function httpChunkSize(?string $httpChunkSize): self
    {
        $new = clone $this;
        $new->httpChunkSize = $httpChunkSize;

        return $new;
    }

    /**
     * Download playlist videos in reverse order.
     */
    public function playlistReverse(bool $playlistReverse): self
    {
        $new = clone $this;
        $new->playlistReverse = $playlistReverse;
        if ($playlistReverse) {
            $new->playlistRandom = false;
        }

        return $new;
    }

    /**
     * Download playlist videos in random order.
     */
    public function playlistRandom(bool $playlistRandom): self
    {
        $new = clone $this;
        $new->playlistRandom = $playlistRandom;
        if ($playlistRandom) {
            $new->playlistReverse = false;
        }

        return $new;
    }

    /**
     * Set file xattribute ytdl.filesize with expected file size.
     */
    public function xattrSetFilesize(?string $xattrSetFilesize): self
    {
        $new = clone $this;
        $new->xattrSetFilesize = $xattrSetFilesize;

        return $new;
    }

    /**
     * Use the native HLS downloader instead of ffmpeg.
     */
    public function hlsPreferNative(bool $hlsPreferNative): self
    {
        $new = clone $this;
        $new->hlsPreferNative = $hlsPreferNative;

        return $new;
    }

    /**
     * Use ffmpeg instead of the native HLS downloader.
     */
    public function hlsPreferFFmpeg(bool $hlsPreferFFmpeg): self
    {
        $new = clone $this;
        $new->hlsPreferFFmpeg = $hlsPreferFFmpeg;

        return $new;
    }

    /**
     * Use the mpegts container for HLS videos, allowing to play the video while
     * downloading (some players may not be able to play it).
     */
    public function hlsUseMpegts(bool $hlsUseMpegts): self
    {
        $new = clone $this;
        $new->hlsUseMpegts = $hlsUseMpegts;

        return $new;
    }

    /**
     * Use the specified external downloader.
     * Currently supports: aria2c, avconv, axel, curl, ffmpeg, httpie, wget.
     */
    public function externalDownloader(?string $externalDownloader): self
    {
        $new = clone $this;
        $new->externalDownloader = $externalDownloader;

        return $new;
    }

    /**
     * Give these arguments to the external downloader.
     */
    public function externalDownloaderArgs(?string $externalDownloaderArgs): self
    {
        $new = clone $this;
        $new->externalDownloaderArgs = $externalDownloaderArgs;

        return $new;
    }

    /**
     * File containing URLs to download ('-' for stdin), one URL per line. Lines
     * starting with '#', ';' or ']' are considered as comments and ignored.
     */
    public function batchFile(?string $batchFile): self
    {
        $new = clone $this;
        $new->batchFile = $batchFile;

        return $new;
    }

    /**
     * Use only video ID in file name.
     */
    public function id(bool $id): self
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    /**
     * Output filename template.
     *
     * @see https://github.com/ytdl-org/youtube-dl#output-template
     */
    public function output(string $output): self
    {
        if (strpos($output, '/') !== false || strpos($output, '\\')) {
            throw new InvalidArgumentException('Providing download path via `output` option is prohibited. Set the download path when creating Options object or calling `downloadPath` method.');
        }

        $new = clone $this;
        $new->output = $output;

        return $new;
    }

    /**
     * Specify the start value for %(autonumber)s (default is 1).
     */
    public function autoNumberStart(?int $autoNumberStart): self
    {
        $new = clone $this;
        $new->autoNumberStart = $autoNumberStart;

        return $new;
    }

    /**
     * Restrict filenames to only ASCII characters, and avoid "&" and spaces in
     * filenames.
     */
    public function restrictFileNames(bool $restrictFilenames): self
    {
        $new = clone $this;
        $new->restrictFilenames = $restrictFilenames;

        return $new;
    }

    /**
     * Do not overwrite files.
     */
    public function noOverwrites(bool $noOverwrites): self
    {
        $new = clone $this;
        $new->noOverwrites = $noOverwrites;

        return $new;
    }

    /**
     * Force resume of partially downloaded files. By default, youtube-dl will
     * resume downloads if possible.
     */
    public function continue(bool $continue): self
    {
        $new = clone $this;
        $new->continue = $continue;
        if ($continue) {
            $new->noContinue = false;
        }

        return $new;
    }

    /**
     * Do not resume partially downloaded files (restart from beginning).
     */
    public function noContinue(bool $noContinue): self
    {
        $new = clone $this;
        $new->noContinue = $noContinue;
        if ($noContinue) {
            $new->continue = false;
        }

        return $new;
    }

    /**
     * Do not use .part files - write directly into output file.
     */
    public function noPart(bool $noPart): self
    {
        $new = clone $this;
        $new->noPart = $noPart;

        return $new;
    }

    /**
     * Do not use the Last-modified header to set the file modification time.
     */
    public function noMtime(bool $noMtime): self
    {
        $new = clone $this;
        $new->noMtime = $noMtime;

        return $new;
    }

    /**
     * Write video description to a .description file.
     */
    public function writeDescription(bool $writeDescription): self
    {
        $new = clone $this;
        $new->writeDescription = $writeDescription;

        return $new;
    }

    /**
     * Write video annotations to a .annotations.xml file.
     */
    public function writeAnnotations(bool $writeAnnotations): self
    {
        $new = clone $this;
        $new->writeAnnotations = $writeAnnotations;

        return $new;
    }

    /**
     * JSON file containing the video information (created with the
     * "--write-info-json" option).
     */
    public function loadInfoJson(?string $loadInfoJson): self
    {
        $new = clone $this;
        $new->loadInfoJson = $loadInfoJson;

        return $new;
    }

    /**
     * File to read cookies from and dump cookie jar in.
     */
    public function cookies(?string $cookies): self
    {
        $new = clone $this;
        $new->cookies = $cookies;

        return $new;
    }

    /**
     * Location in the filesystem where youtube-dl can store some downloaded
     * information permanently. By default $XDG_CACHE_HOME/youtube-dl or
     * ~/.cache/youtube-dl . At the moment, only YouTube player files (for
     * videos with obfuscated signatures) are cached, but that may change.
     */
    public function cacheDir(?string $cacheDir): self
    {
        $new = clone $this;
        $new->cacheDir = $cacheDir;

        return $new;
    }

    /**
     * Disable filesystem caching.
     */
    public function noCacheDir(bool $noCacheDir): self
    {
        $new = clone $this;
        $new->noCacheDir = $noCacheDir;

        return $new;
    }

    /**
     * Delete all filesystem cache files.
     */
    public function rmCacheDir(bool $rmCacheDir): self
    {
        $new = clone $this;
        $new->rmCacheDir = $rmCacheDir;

        return $new;
    }

    /**
     * Write thumbnail image to disk.
     */
    public function writeThumbnail(bool $writeThumbnail): self
    {
        $new = clone $this;
        $new->writeThumbnail = $writeThumbnail;

        return $new;
    }

    /**
     * Write all thumbnail image formats to disk.
     */
    public function writeAllThumbnails(bool $writeAllThumbnails): self
    {
        $new = clone $this;
        $new->writeAllThumbnails = $writeAllThumbnails;

        return $new;
    }

    /**
     * Do not download the video.
     */
    public function skipDownload(bool $skipDownload): self
    {
        $new = clone $this;
        $new->skipDownload = $skipDownload;

        return $new;
    }

    public function getSkipDownload(): bool
    {
        return $this->skipDownload;
    }

    /**
     * Print various debugging information.
     */
    public function verbose(bool $verbose): self
    {
        $new = clone $this;
        $new->verbose = $verbose;

        return $new;
    }

    /**
     * Print downloaded pages encoded using base64 to debug problems (very
     * verbose).
     */
    public function writePages(bool $writePages): self
    {
        $new = clone $this;
        $new->writePages = $writePages;

        return $new;
    }

    /**
     * Display sent and read HTTP traffic.
     */
    public function printTraffic(bool $printTraffic): self
    {
        $new = clone $this;
        $new->printTraffic = $printTraffic;

        return $new;
    }

    /**
     * Contact the youtube-dl server for debugging.
     */
    public function callHome(bool $callHome): self
    {
        $new = clone $this;
        $new->callHome = $callHome;
        if ($callHome) {
            $new->noCallHome = false;
        }

        return $new;
    }

    /**
     * Do NOT contact the youtube-dl server for debugging.
     */
    public function noCallHome(bool $noCallHome): self
    {
        $new = clone $this;
        $new->noCallHome = $noCallHome;
        if ($noCallHome) {
            $new->callHome = false;
        }

        return $new;
    }

    /**
     * Force the specified encoding (experimental).
     */
    public function encoding(?string $encoding): self
    {
        $new = clone $this;
        $new->encoding = $encoding;

        return $new;
    }

    /**
     * Suppress HTTPS certificate validation.
     */
    public function noCheckCertificate(bool $noCheckCertificate): self
    {
        $new = clone $this;
        $new->noCheckCertificate = $noCheckCertificate;

        return $new;
    }

    /**
     * Use an unencrypted connection to retrieve information about the video.
     * (Currently supported only for YouTube).
     */
    public function preferInsecure(bool $preferInsecure): self
    {
        $new = clone $this;
        $new->preferInsecure = $preferInsecure;

        return $new;
    }

    /**
     * Specify a custom user agent.
     */
    public function userAgent(?string $userAgent): self
    {
        $new = clone $this;
        $new->userAgent = $userAgent;

        return $new;
    }

    /**
     * Specify a custom referer, use if the video access is restricted to one
     * domain.
     */
    public function referer(?string $referer): self
    {
        $new = clone $this;
        $new->referer = $referer;

        return $new;
    }

    public function header(string $header, string $value): self
    {
        $new = clone $this;
        $new->headers[$header] = $value;

        return $new;
    }

    /**
     * @param array<string, string> $headers
     */
    public function headers(array $headers): self
    {
        $new = clone $this;
        $new->headers = $headers;

        return $new;
    }

    /**
     * Number of seconds to sleep before each download when used alone or a
     * lower bound of a range for randomized sleep before each download (minimum
     * possible number of seconds to sleep) when used along with
     * `maxSleepInterval`.
     */
    public function sleepInterval(?int $sleepInterval): self
    {
        $new = clone $this;
        $new->sleepInterval = $sleepInterval;

        return $new;
    }

    /**
     * Upper bound of a range for randomized sleep before each download (maximum
     * possible number of seconds to sleep). Must only be used along with
     * `sleepInterval`.
     */
    public function maxSleepInterval(?int $maxSleepInterval): self
    {
        $new = clone $this;
        $new->maxSleepInterval = $maxSleepInterval;

        return $new;
    }

    public function format(?string $format): self
    {
        $new = clone $this;
        $new->format = $format;

        return $new;
    }

    /**
     * Do not download the DASH manifests and related data on YouTube videos.
     */
    public function youtubeSkipDashManifest(bool $youtubeSkipDashManifest): self
    {
        $new = clone $this;
        $new->youtubeSkipDashManifest = $youtubeSkipDashManifest;

        return $new;
    }

    /**
     * If a merge is required (e.g. bestvideo+bestaudio), output to given
     * container format. One of mkv, mp4, ogg, webm, flv.
     * Ignored if no merge is required.
     *
     * @phpstasn-param self::MERGE_OUTPUT_FORMAT_*|null $mergeOutputFormat
     */
    public function mergeOutputFormat(?string $mergeOutputFormat): self
    {
        if ($mergeOutputFormat !== null && !in_array($mergeOutputFormat, static::MERGE_OUTPUT_FORMATS, true)) {
            throw new InvalidArgumentException(sprintf('Option `mergeOutputFormat` expected one of: %s. Got: %s.', implode(', ', array_map(static fn ($v) => '"'.$v.'"', static::MERGE_OUTPUT_FORMATS)), '"'.$mergeOutputFormat.'"'));
        }

        $new = clone $this;
        $new->mergeOutputFormat = $mergeOutputFormat;

        return $new;
    }

    /**
     * Write subtitle file.
     */
    public function writeSub(bool $writeSub): self
    {
        $new = clone $this;
        $new->writeSub = $writeSub;

        return $new;
    }

    /**
     * Write automatically generated subtitle file (YouTube only).
     */
    public function writeAutoSub(bool $writeAutoSub): self
    {
        $new = clone $this;
        $new->writeAutoSub = $writeAutoSub;

        return $new;
    }

    /**
     * Download all the available subtitles of the video.
     */
    public function allSubs(bool $allSubs): self
    {
        $new = clone $this;
        $new->allSubs = $allSubs;

        return $new;
    }

    /**
     * Subtitle format, accepts formats preference, for example: "srt" or
     * "ass/srt/best".
     */
    public function subFormat(?string $subFormat): self
    {
        $new = clone $this;
        $new->subFormat = $subFormat;

        return $new;
    }

    /**
     * Languages of the subtitles to download (optional).
     * Use `YoutubeDl::listSubs($url)` to get available language tags.
     */
    public function subLang(array $subLang): self
    {
        $new = clone $this;
        $new->subLang = $subLang;

        return $new;
    }

    /**
     * Login with this account ID and password.
     */
    public function authenticate(?string $username, ?string $password): self
    {
        $new = clone $this;
        $new->username = $username;
        $new->password = $password;
        if (($username === null && $password !== null) || ($username !== null && $password === null)) {
            // Without a password `youtube-dl` would enter ineractive mode.
            throw new InvalidArgumentException('Authentication username and password must be provided when configuring account details.');
        }

        return $new;
    }

    /**
     * Two-factor authentication code.
     */
    public function twoFactor(?string $twoFactor): self
    {
        $new = clone $this;
        $new->twoFactor = $twoFactor;

        return $new;
    }

    /**
     * Use .netrc authentication data.
     */
    public function netrc(bool $netrc): self
    {
        $new = clone $this;
        $new->netrc = $netrc;

        return $new;
    }

    /**
     * Video password (vimeo, smotri, youku).
     */
    public function videoPassword(?string $videoPassword): self
    {
        $new = clone $this;
        $new->videoPassword = $videoPassword;

        return $new;
    }

    /**
     * Adobe Pass multiple-system operator (TV provider) identifier.
     * Use `YoutubeDl::getMultipleSystemOperatorsList()` to get the list of
     * all available MSOs.
     */
    public function apMso(?string $apMso): self
    {
        $new = clone $this;
        $new->apMso = $apMso;

        return $new;
    }

    /**
     * Multiple-system operator account username and password.
     */
    public function apLogin(?string $apUsername, ?string $apPassword): self
    {
        $new = clone $this;
        $new->apUsername = $apUsername;
        $new->apPassword = $apPassword;

        if ($apUsername !== null && $apPassword === null) {
            // Without a password `youtube-dl` would enter ineractive mode.
            throw new InvalidArgumentException('MSO password must be provided when configuring account details.');
        }

        return $new;
    }

    public function extractAudio(bool $extractAudio): self
    {
        $new = clone $this;
        $new->extractAudio = $extractAudio;

        return $new;
    }

    public function getExtractAudio(): bool
    {
        return $this->extractAudio;
    }

    /**
     * @phpstan-param self::AUDIO_FORMAT_*|null $audioFormat
     */
    public function audioFormat(?string $audioFormat): self
    {
        if ($audioFormat !== null && !in_array($audioFormat, static::AUDIO_FORMATS, true)) {
            throw new InvalidArgumentException(sprintf('Option `audioFormat` expected one of: %s. Got: %s.', implode(', ', array_map(static fn ($v) => '"'.$v.'"', static::AUDIO_FORMATS)), '"'.$audioFormat.'"'));
        }

        $new = clone $this;
        $new->audioFormat = $audioFormat;

        return $new;
    }

    public function audioQuality(?string $audioQuality): self
    {
        $new = clone $this;
        $new->audioQuality = $audioQuality;

        return $new;
    }

    /**
     * Remux the video into another container if necessary (currently supported:
     * mp4|mkv|flv|webm|mov|avi|mp3|mka|m4a|ogg|opus). If target container does
     * not support the video/audio codec, remuxing will fail. You can specify
     * multiple rules; Eg. "aac>m4a/mov>mp4/mkv" will remux aac to * m4a,
     * mov to mp4 and anything else to mkv.
     */
    public function remuxVideo(?string $remuxVideo): self
    {
        $new = clone $this;
        $new->remuxVideo = $remuxVideo;

        return $new;
    }

    public function recodeVideo(?string $recodeVideo): self
    {
        if ($recodeVideo !== null && !in_array($recodeVideo, static::RECODE_VIDEO_FORMATS, true)) {
            throw new InvalidArgumentException(sprintf('Option `recodeVideo` expected one of: %s. Got: %s.', implode(', ', array_map(static fn ($v) => '"'.$v.'"', static::RECODE_VIDEO_FORMATS)), '"'.$recodeVideo.'"'));
        }

        $new = clone $this;
        $new->recodeVideo = $recodeVideo;

        return $new;
    }

    public function keepVideo(bool $keepVideo): self
    {
        $new = clone $this;
        $new->keepVideo = $keepVideo;

        return $new;
    }

    public function noPostOverwrites(bool $noPostOverwrites): self
    {
        $new = clone $this;
        $new->noPostOverwrites = $noPostOverwrites;

        return $new;
    }

    public function embedSubs(bool $embedSubs): self
    {
        $new = clone $this;
        $new->embedSubs = $embedSubs;

        return $new;
    }

    public function embedThumbnail(bool $embedThumbnail): self
    {
        $new = clone $this;
        $new->embedThumbnail = $embedThumbnail;

        return $new;
    }

    public function addMetadata(bool $addMetadata): self
    {
        $new = clone $this;
        $new->addMetadata = $addMetadata;

        return $new;
    }

    public function metadataFromTitle(string $metadataFromTitle): self
    {
        $new = clone $this;
        $new->metadataFromTitle = $metadataFromTitle;

        return $new;
    }

    public function xattrs(bool $xattrs): self
    {
        $new = clone $this;
        $new->xattrs = $xattrs;

        return $new;
    }

    public function preferAvconv(bool $preferAvconv): self
    {
        $new = clone $this;
        $new->preferAvconv = $preferAvconv;

        return $new;
    }

    public function preferFFmpeg(bool $preferFFmpeg): self
    {
        $new = clone $this;
        $new->preferFFmpeg = $preferFFmpeg;

        return $new;
    }

    public function ffmpegLocation(?string $ffmpegLocation): self
    {
        $new = clone $this;
        $new->ffmpegLocation = $ffmpegLocation;

        return $new;
    }

    public function exec(?string $exec): self
    {
        $new = clone $this;
        $new->exec = $exec;

        return $new;
    }

    /**
     * @phpstan-param self::SUBTITLE_FORMAT_*|null $subsFormat
     */
    public function convertSubsFormat(?string $subsFormat): self
    {
        if ($subsFormat !== null && !in_array($subsFormat, static::SUBTITLE_FORMATS, true)) {
            throw new InvalidArgumentException(sprintf('Option `convertSubsFormat` expected one of: %s. Got: %s.', implode(', ', array_map(static fn ($v) => '"'.$v.'"', static::SUBTITLE_FORMATS)), '"'.$subsFormat.'"'));
        }

        $new = clone $this;
        $new->convertSubsFormat = $subsFormat;

        return $new;
    }

    public function url(string $url, string ...$urls): self
    {
        $new = clone $this;
        $new->url = [$url, ...$urls];

        return $new;
    }

    public function getUrl(): array
    {
        return $this->url;
    }

    public function toArray(): array
    {
        return [
            // Network Options
            'proxy' => $this->proxy,
            'socket-timeout' => $this->socketTimeout,
            'source-address' => $this->sourceAddress,
            'force-ipv4' => $this->forceIpV4,
            'force-ipv6' => $this->forceIpV6,
            // Geo Restriction
            'geo-verification-proxy' => $this->geoVerificationProxy,
            'geo-bypass' => $this->geoBypass,
            'no-geo-bypass' => $this->noGeoBypass,
            'geo-bypass-country' => $this->geoBypassCountry,
            'geo-bypass-ip-block' => $this->geoBypassIpBlock,
            // Video Selection
            'playlist-start' => $this->playlistStart,
            'playlist-end' => $this->playlistEnd,
            'playlist-items' => $this->playlistItems,
            'match-title' => $this->matchTitle,
            'reject-title' => $this->rejectTitle,
            'max-downloads' => $this->maxDownloads,
            'min-filesize' => $this->minFileSize,
            'max-filesize' => $this->maxFileSize,
            'date' => $this->date,
            'datebefore' => $this->dateBefore,
            'dateafter' => $this->dateAfter,
            'min-views' => $this->minViews,
            'max-views' => $this->maxViews,
            'match-filter' => $this->matchFilter,
            'no-playlist' => $this->noPlaylist,
            'yes-playlist' => $this->yesPlaylist,
            'age-limit' => $this->ageLimit,
            // Download Options
            'limit-rate' => $this->limitRate,
            'retries' => $this->retries,
            'fragment-retries' => $this->fragmentRetries,
            'skip-unavailable-fragments' => $this->skipUnavailableFragments,
            'keep-fragments' => $this->keepFragments,
            'buffer-size' => $this->bufferSize,
            'no-resize-buffer' => $this->noResizeBuffer,
            'http-chunk-size' => $this->httpChunkSize,
            'playlist-reverse' => $this->playlistReverse,
            'playlist-random' => $this->playlistRandom,
            'xattr-set-filesize' => $this->xattrSetFilesize,
            'hls-prefer-native' => $this->hlsPreferNative,
            'hls-prefer-ffmpeg' => $this->hlsPreferFFmpeg,
            'hls-use-mpegts' => $this->hlsUseMpegts,
            'external-downloader' => $this->externalDownloader,
            'external-downloader-args' => $this->externalDownloaderArgs,
            // Filesystem Options
            'batch-file' => $this->batchFile,
            'id' => $this->id,
            'output' => $this->downloadPath.'/'.$this->output,
            'autonumber-start' => $this->autoNumberStart,
            'restrict-filenames' => $this->restrictFilenames,
            'no-overwrites' => $this->noOverwrites,
            'continue' => $this->continue,
            'no-continue' => $this->noContinue,
            'no-part' => $this->noPart,
            'no-mtime' => $this->noMtime,
            'write-description' => $this->writeDescription,
            'write-annotations' => $this->writeAnnotations,
            'load-info-json' => $this->loadInfoJson,
            'cookies' => $this->cookies,
            'cache-dir' => $this->cacheDir,
            'no-cache-dir' => $this->noCacheDir,
            'rm-cache-dir' => $this->rmCacheDir,
            // Thumbnail Images Options
            'write-thumbnail' => $this->writeThumbnail,
            'write-all-thumbnails' => $this->writeAllThumbnails,
            // Verbosity / Simulation Options
            'skip-download' => $this->skipDownload,
            'verbose' => $this->verbose,
            'write-pages' => $this->writePages,
            'print-traffic' => $this->printTraffic,
            'call-home' => $this->callHome,
            'no-call-home' => $this->noCallHome,
            // Workaround Options
            'encoding' => $this->encoding,
            'no-check-certificate' => $this->noCheckCertificate,
            'prefer-insecure' => $this->preferInsecure,
            'user-agent' => $this->userAgent,
            'referer' => $this->referer,
            'add-header' => $this->headers,
            'sleep-interval' => $this->sleepInterval,
            'max-sleep-interval' => $this->maxSleepInterval,
            // Video Format Options
            'format' => $this->format,
            'youtube-skip-dash-manifest' => $this->youtubeSkipDashManifest,
            'merge-output-format' => $this->mergeOutputFormat,
            // Subtitle Options
            'write-sub' => $this->writeSub,
            'write-auto-sub' => $this->writeAutoSub,
            'all-subs' => $this->allSubs,
            'sub-format' => $this->subFormat,
            'sub-lang' => $this->subLang,
            // Authentication Options
            'username' => $this->username,
            'password' => $this->password,
            'twofactor' => $this->twoFactor,
            'netrc' => $this->netrc,
            'video-password' => $this->videoPassword,
            // Adobe Pass Options
            'ap-mso' => $this->apMso,
            'ap-username' => $this->apUsername,
            'ap-password' => $this->apPassword,
            // Post-processing Options
            'extract-audio' => $this->extractAudio,
            'audio-format' => $this->audioFormat,
            'audio-quality' => $this->audioQuality,
            'remux-video' => $this->remuxVideo,
            'recode-video' => $this->recodeVideo,
            'postprocessor-args' => $this->postProcessorArgs,
            'keep-video' => $this->keepVideo,
            'no-post-overwrites' => $this->noPostOverwrites,
            'embed-subs' => $this->embedSubs,
            'embed-thumbnail' => $this->embedThumbnail,
            'add-metadata' => $this->addMetadata,
            'metadata-from-title' => $this->metadataFromTitle,
            'xattrs' => $this->xattrs,
            'prefer-avconv' => $this->preferAvconv,
            'prefer-ffmpeg' => $this->preferFFmpeg,
            'ffmpeg-location' => $this->ffmpegLocation,
            'exec' => $this->exec,
            'convert-subs-format' => $this->convertSubsFormat,
            'url' => $this->url,
        ];
    }

    public static function create(): self
    {
        return new self();
    }
}
