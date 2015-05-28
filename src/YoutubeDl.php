<?php
namespace YoutubeDl;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use YoutubeDl\Mapper\Mapper;

class YoutubeDl
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $downloadPath;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var array
     */
    protected $processOptions = [];

    /**
     * @var callable
     */
    protected $debug;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * Set download path
     *
     * @param string $downloadPath
     */
    public function setDownloadPath($downloadPath)
    {
        $this->downloadPath = $downloadPath;
    }

    /**
     * Get download path
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return $this->downloadPath;
    }

    /**
     * Set timeout
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set process options
     *
     * @param array $options
     */
    public function setProcessOptions(array $options)
    {
        $this->processOptions = $options;
    }

    /**
     * Get process options
     *
     * @return array
     */
    public function getProcessOptions()
    {
        return $this->processOptions;
    }

    /**
     * Enable debugging
     *
     * $obj->debug(function ($type, $buffer) {
     *     if (\Symfony\Component\Process\Process::ERR === $type) {
     *         echo 'ERR > ' . $buffer;
     *     } else {
     *         echo 'OUT > ' . $buffer;
     *     }
     * });
     *
     * @param callable|null $debug
     */
    public function debug(callable $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Get command line
     *
     * @return string
     */
    public function getCommandLine()
    {
        $c = 'youtube-dl ';

        foreach ($this->options as $option => $value) {
            if ($option == 'add-header') {
                foreach ($value as $header) {
                    $c .= '--' . $option . ' ' . $header . ' ';
                }
            } else {
                $c .= '--' . $option . (!is_bool($value) ? ' ' . $value : '') . ' ';
            }
        }

        $c .= '--print-json --ignore-config';

        return $c;
    }

    /**
     * Download
     *
     * @param mixed $url Url or array of urls to download
     *
     * @return Entity\Video[]|Entity\Video
     * @throws PrivateVideoException
     * @throws CopyrightException
     * @throws NotFoundException
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Exception
     */
    public function download($url)
    {
        if (is_array($url)) {
            $url = implode(' ', $url);
        }

        $process = new Process(sprintf('%s %s', $this->getCommandLine(), escapeshellarg($url)), $this->downloadPath, null, null, $this->timeout, $this->processOptions);

        try {
            $process->mustRun(is_callable($this->debug) ? $this->debug : null);
        } catch (\Exception $e) {
            $message = $e->getMessage();

            if (preg_match('/please sign in to view this video/i', $message)) {
                throw new PrivateVideoException();
            } elseif (preg_match('/copyright infringement/i', $message)) {
                throw new CopyrightException();
            } elseif (preg_match('/this video does not exist|404/i', $message)) {
                throw new NotFoundException();
            } else {
                throw $e;
            }
        }

        if ($parts = explode("\n", trim($process->getOutput()))) {
            $mapper = new Mapper($this->downloadPath ?: getcwd());

            if (count($parts) > 1) {
                $videos = [];

                foreach ($parts as $part) {
                    $videos[] = $mapper->map($this->jsonDecode($part));
                }

                return $videos;
            }

            return $mapper->map($this->jsonDecode($parts[0]));
        }

        return false;
    }

    /**
     * Get supported extractors list
     *
     * @return array
     */
    public function getExtractorsList()
    {
        $process = new Process('youtube-dl --list-extractors');
        $process->mustRun(is_callable($this->debug) ? $this->debug : null);

        $list = array_filter(explode("\n", $process->getOutput()));

        return $list;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $options = [
            // General options
            'ignore-errors' => 'bool',
            'abort-on-error' => 'bool',
            'default-search' => 'string',
            //'ignore-config' => 'bool',
            // Network options
            'proxy' => 'string',
            'socket-timeout' => 'int',
            'source-address' => 'string',
            'force-ipv4' => 'bool',
            'force-ipv6' => 'bool',
            // Video selection options
            'playlist-start' => 'int',
            'playlist-end' => 'int|string',
            'playlist-items' => 'string',
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
            'no-playlist' => 'bool',
            'yes-playlist' => 'bool',
            'download-archive' => 'string',
            'include-ads' => 'bool',
            // Download Options
            'rate-limit' => 'string',
            'retries' => 'int|string',
            'buffer-size' => 'string',
            'no-resize-buffer' => 'bool',
            'playlist-reverse' => 'bool',
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
            'write-info-json' => 'bool',
            'write-annotations' => 'bool',
            'load-info' => 'string',
            'cookies' => 'string',
            'cache-dir' => 'string',
            'no-cache-dir' => 'bool',
            'rm-cache-dir' => 'bool',
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
            'all-formats' => 'bool',
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
        ];

        $resolver->setDefined(array_keys($options));

        foreach ($options as $option => $types) {
            $resolver->setAllowedTypes($option, explode('|', $types));
        }

        $resolver->setAllowedValues('retries', function ($value) {
            if (is_string($value) && $value != 'infinite') {
                return false;
            }

            return true;
        });

        $resolver->setAllowedValues('external-downloader', ['aria2c', 'curl', 'wget']);

        $resolver->setAllowedValues('playlist-end', function ($value) {
            if (is_string($value) && 'last' != $value) {
                return false;
            }

            return true;
        });

        $resolver->setAllowedValues('audio-format', ['best', 'aac', 'vorbis', 'mp3', 'm4a', 'opus', 'wav']);

        $resolver->setAllowedValues('ffmpeg-location', function ($value) {
            if (!is_file($value) && !is_dir($value)) {
                return false;
            }

            return true;
        });

        $resolver->setNormalizer('add-header', function ($options, $value) {
            foreach ($value as $k => $v) {
                if (false === strpos($v, ':')) {
                    unset($value[$k]);
                }
            }

            return $value;
        });

        $resolver->setNormalizer('output', function ($options, $value) {
            return sprintf('"%s"', $value);
        });
    }

    /**
     * Decode json to an associative array
     *
     * @param string $data
     *
     * @return array
     */
    protected function jsonDecode($data)
    {
        return json_decode($data, true);
    }
}
