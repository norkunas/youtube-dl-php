<?php

declare(strict_types=1);

namespace YoutubeDl;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use YoutubeDl\Entity\Video;
use YoutubeDl\Exception\AccountTerminatedException;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\ExecutableNotFoundException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use YoutubeDl\Exception\UrlNotSupportedException;
use YoutubeDl\Exception\YoutubeDlException;
use YoutubeDl\Exception\GeoBlockedException;

class YoutubeDl
{
    const PROGRESS_PATTERN = '#\[download\]\s+(?<percentage>\d+(?:\.\d+)?%)\s+of\s+(?<size>\d+(?:\.\d+)?(?:K|M|G)iB)(?:\s+at\s+(?<speed>\d+(?:\.\d+)?(?:K|M|G)iB/s))?(?:\s+ETA\s+(?<eta>[\d:]{2,8}))#i';

    const RECODE_VIDEO_FORMATS = ['mp4', 'flv', 'ogg', 'webm', 'mkv', 'avi'];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $binPath;

    /**
     * @var string
     */
    protected $pythonPath;

    /**
     * @var string
     */
    protected $downloadPath;

    /**
     * @var callable
     */
    protected $debug;

    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * @var array
     */
    protected $allowedAudioFormats = ['best', 'aac', 'vorbis', 'mp3', 'm4a', 'opus', 'wav'];

    /**
     * @var callable
     */
    private $progress;

    private static $blacklist = [
        '#soundcloud.com/.+/sets.+#',
    ];

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    public function setBinPath(string $binPath)
    {
        $this->binPath = $binPath;
    }

    public function setPythonPath(string $pythonPath)
    {
        $this->pythonPath = $pythonPath;
    }

    /**
     * @param string $downloadPath Download path without trailing slash
     */
    public function setDownloadPath(string $downloadPath)
    {
        $this->downloadPath = $downloadPath;
    }

    public function debug(callable $debug)
    {
        $this->debug = $debug;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function onProgress(callable $onProgress)
    {
        $this->progress = $onProgress;
    }

    public function download(string $url): Video
    {
        if (!$this->downloadPath) {
            throw new \RuntimeException('No download path was set.');
        }

        if (!$this->isUrlSupported($url)) {
            throw new UrlNotSupportedException("Provided url '$url' is not supported.");
        }

        $arguments = [
            $url,
            '--no-playlist',
            '--ignore-config',
            '--write-info-json',
        ];

        foreach ($this->options as $option => $value) {
            if ('add-header' === $option) {
                foreach ($value as $header) {
                    $arguments[] = sprintf('--%s=%s', $option, $header);
                }
            } elseif (is_bool($value)) {
                $arguments[] = sprintf('--%s', $option);
            } else {
                $arguments[] = sprintf('--%s=%s', $option, $value);
            }
        }

        $process = $this->createProcess($arguments);

        try {
            $process->mustRun(function ($type, $buffer) {
                $debug = $this->debug;
                $progress = $this->progress;

                if (is_callable($debug)) {
                    $debug($type, $buffer);
                }

                if (is_callable($progress) && Process::OUT === $type && preg_match(self::PROGRESS_PATTERN, $buffer, $matches)) {
                    unset($matches[0], $matches[1], $matches[2], $matches[3], $matches[4]);

                    $progress($matches);
                }
            });
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }

        return $this->processDownload($process);
    }

    public function getExtractorsList(): array
    {
        $process = $this->createProcess(['--list-extractors']);
        $process->mustRun(is_callable($this->debug) ? $this->debug : null);

        return array_filter(explode("\n", $process->getOutput()));
    }

    protected function jsonDecode($data): array
    {
        $decoded = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new YoutubeDlException(sprintf('Response can\'t be decoded: %s.', $data));
        }

        return $decoded;
    }

    protected function processDownload(Process $process): Video
    {
        if (!preg_match('/Writing video description metadata as JSON to:\s(.+)/', $process->getOutput(), $m)) {
            throw new YoutubeDlException('Failed to detect metadata file.');
        }

        $metadataFile = $this->downloadPath.'/'.$m[1];

        $videoData = $this->jsonDecode(trim(file_get_contents($metadataFile)));

        @unlink($metadataFile);

        if (!isset($this->options['skip-download']) || false === $this->options['skip-download']) {
            if (isset($this->options['extract-audio']) && true === $this->options['extract-audio']) {
                $file = $this->findFile($videoData['_filename'], implode('|', $this->allowedAudioFormats));
                $videoData['_filename'] = pathinfo($file, PATHINFO_BASENAME);
            } elseif (isset($this->options['recode-video'])) {
                $file = $this->findFile($videoData['_filename'], implode('|', self::RECODE_VIDEO_FORMATS));
                $videoData['_filename'] = pathinfo($file, PATHINFO_BASENAME);
            } elseif (preg_match('/merged into mkv/', $process->getErrorOutput())) {
                $videoData['_filename'] = pathinfo($this->findFile($videoData['_filename'], 'mkv'), PATHINFO_BASENAME);
            }

            $videoData['file'] = new \SplFileInfo($this->downloadPath.'/'.$videoData['_filename']);
        } else {
            $videoData['file'] = null;
        }

        return new Video($videoData);
    }

    protected function handleException(\Exception $e): \Exception
    {
        $message = $e->getMessage();

        if (preg_match('/please sign in to view this video|video is protected by a password/i', $message)) {
            return new PrivateVideoException();
        } elseif (preg_match('/copyright infringement/i', $message)) {
            return new CopyrightException();
        } elseif (preg_match('/this video does not exist|404/i', $message)) {
            return new NotFoundException();
        } elseif (preg_match('/account associated with this video has been terminated/', $message)) {
            return new AccountTerminatedException();
        } elseif (preg_match('/The uploader has not made this video available in your country./', $message)) {
            return new GeoBlockedException();
        }

        return $e;
    }

    protected function createProcess(array $arguments = []): Process
    {
        $binPath = $this->binPath ?: (new ExecutableFinder())->find('youtube-dl');

        if (null === $binPath) {
            throw new ExecutableNotFoundException('"youtube-dl" executable was not found. Did you forgot to add it to environment variables? Or set it via $yt->setBinPath(\'/usr/bin/youtube-dl\').');
        }

        array_unshift($arguments, $binPath);

        if ($this->pythonPath) {
            array_unshift($arguments, $this->pythonPath);
        }

        $process = new Process($arguments);
        $process->setTimeout($this->timeout);

        if ($this->downloadPath) {
            $process->setWorkingDirectory($this->downloadPath);
        }

        return $process;
    }

    protected function findFile(string $fileName, string $extension)
    {
        $iterator = new \RegexIterator(new \DirectoryIterator($this->downloadPath), sprintf('/%s\.%s$/ui', preg_quote(pathinfo($fileName, PATHINFO_FILENAME), '/'), '('.$extension.')'), \RegexIterator::GET_MATCH);
        $iterator->rewind();

        return $iterator->current()[0];
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $options = [
            // General options
            'ignore-errors' => 'bool',
            'abort-on-error' => 'bool',
            'default-search' => 'string',
            'force-generic-extractor' => 'bool',
            // Network options
            'proxy' => 'string',
            'socket-timeout' => 'int',
            'source-address' => 'string',
            'force-ipv4' => 'bool',
            'force-ipv6' => 'bool',
            // Geo Restriction options
            'geo-verification-proxy' => 'string',
            'geo-bypass' => 'bool',
            'no-geo-bypass' => 'bool',
            'geo-bypass-country' => 'string',
            'geo-bypass-ip-block' => 'string',
            // Video selection options
            'match-title' => 'string',
            'reject-title' => 'string',
            'max-downloads' => 'int',
            'min-filesize' => 'string',
            'max-filesize' => 'string',
            'date' => 'string',
            'datebefore' => 'string',
            'dateafter' => 'string',
            'min-views' => 'int',
            'max-views' => 'int',
            'match-filter' => 'string',
            'download-archive' => 'string',
            'include-ads' => 'bool',
            // Download Options
            'rate-limit' => 'string',
            'retries' => 'int|string',
            'buffer-size' => 'string',
            'no-resize-buffer' => 'bool',
            'xattr-set-filesize' => 'bool',
            'hls-prefer-native' => 'bool',
            'external-downloader' => 'string',
            'external-downloader-args' => 'string',
            // Filesystem Options
            'batch-file' => 'string',
            'output' => 'string',
            'autonumber-size' => 'int',
            'restrict-filenames' => 'bool',
            'no-overwrites' => 'bool',
            'continue' => 'bool',
            'no-continue' => 'bool',
            'no-part' => 'bool',
            'no-mtime' => 'bool',
            'write-description' => 'bool',
            'write-annotations' => 'bool',
            'cookies' => 'string',
            'cache-dir' => 'string',
            'no-cache-dir' => 'bool',
            'rm-cache-dir' => 'bool',
            'id' => 'bool',
            // Thumbnail images
            'write-thumbnail' => 'bool',
            'write-all-thumbnails' => 'bool',
            // Verbosity / Simulation Options
            'quiet' => 'bool',
            'no-warnings' => 'bool',
            'simulate' => 'bool',
            'skip-download' => 'bool',
            'call-home' => 'bool',
            'no-call-home' => 'bool',
            // Workarounds
            'encoding' => 'string',
            'no-check-certificate' => 'bool',
            'prefer-insecure' => 'bool',
            'user-agent' => 'string',
            'referer' => 'string',
            'add-header' => 'array',
            'bidi-workaround' => 'bool',
            'sleep-interval' => 'int',
            // Video Format Options
            'format' => 'string',
            'prefer-free-formats' => 'bool',
            'max-quality' => 'string',
            'youtube-skip-dash-manifest' => 'bool',
            'merge-output-format' => 'string',
            // Subtitle Options
            'write-sub' => 'bool',
            'write-auto-sub' => 'bool',
            'all-subs' => 'bool',
            'sub-format' => 'string',
            'sub-lang' => 'string',
            // Authentication Options
            'username' => 'string',
            'password' => 'string',
            'twofactor' => 'string',
            'netrc' => 'bool',
            'video-password' => 'string',
            // Post-processing Options
            'extract-audio' => 'bool',
            'audio-format' => 'string',
            'audio-quality' => 'int',
            'recode-video' => 'string',
            'keep-video' => 'bool',
            'no-post-overwrites' => 'bool',
            'embed-subs' => 'bool',
            'embed-thumbnail' => 'bool',
            'add-metadata' => 'bool',
            'metadata-from-title' => 'string',
            'xattrs' => 'bool',
            'fixup' => 'string',
            'prefer-avconv' => 'bool',
            'prefer-ffmpeg' => 'bool',
            'ffmpeg-location' => 'string',
            'exec' => 'string',
            'convert-subtitles' => 'string',
            // Ffmpeg postprocessor
            'postprocessor-args' => 'string',
        ];

        $resolver->setDefined(array_keys($options));

        foreach ($options as $option => $types) {
            $resolver->setAllowedTypes($option, explode('|', $types));
        }

        $resolver->setAllowedValues('retries', function ($value) {
            if (is_string($value) && 'infinite' !== $value) {
                return false;
            }

            return true;
        });

        $resolver->setAllowedValues('external-downloader', ['aria2c', 'avconv', 'axel', 'curl', 'ffmpeg', 'httpie', 'wget']);

        $resolver->setAllowedValues('audio-format', $this->allowedAudioFormats);

        $resolver->setAllowedValues('ffmpeg-location', function ($value) {
            if (!is_file($value) && !is_dir($value)) {
                return false;
            }

            return true;
        });

        $resolver->setNormalizer('add-header', function (Options $options, $value) {
            foreach ($value as $k => $v) {
                if (false === strpos($v, ':')) {
                    unset($value[$k]);
                }
            }

            return $value;
        });

        $resolver->setAllowedValues('recode-video', self::RECODE_VIDEO_FORMATS);
    }

    protected function isUrlSupported(string $url): bool
    {
        foreach (self::$blacklist as $pattern) {
            if (preg_match($pattern, $url)) {
                return false;
            }
        }

        return true;
    }
}
