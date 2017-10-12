<?php

namespace YoutubeDl;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use YoutubeDl\Entity\Video;
use YoutubeDl\Exception\AccountTerminatedException;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use YoutubeDl\Exception\UrlNotSupportedException;

class YoutubeDl
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $binPath;

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
     * @var bool
     */
    protected $moveWithPhp = false;

    /**
     * @var callable
     */
    protected $debug;

    /**
     * @var array
     */
    protected $allowedAudioFormats = ['best', 'aac', 'vorbis', 'mp3', 'm4a', 'opus', 'wav'];

    /**
     * Constructor.
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
     * Set bin path.
     *
     * @param string $binPath
     */
    public function setBinPath($binPath)
    {
        $this->binPath = $binPath;
    }

    /**
     * Get bin path.
     *
     * @return string
     */
    public function getBinPath()
    {
        return $this->binPath;
    }

    /**
     * Set download path.
     *
     * @param string $downloadPath
     */
    public function setDownloadPath($downloadPath)
    {
        $this->downloadPath = $downloadPath;
    }

    /**
     * Get download path.
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return $this->downloadPath;
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set process options.
     *
     * @param array $options
     */
    public function setProcessOptions(array $options)
    {
        $this->processOptions = $options;
    }

    /**
     * Get process options.
     *
     * @return array
     */
    public function getProcessOptions()
    {
        return $this->processOptions;
    }

    public function setMoveWithPhp($moveWithPhp)
    {
        $this->moveWithPhp = $moveWithPhp;
    }

    public function getMoveWithPhp()
    {
        return $this->moveWithPhp;
    }

    /**
     * Enable debugging.
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
     * Get command line.
     *
     * @return string
     */
    public function getCommandLine()
    {
        if ($this->binPath) {
            $c = $this->binPath.' ';
        } else {
            $c = 'youtube-dl ';
        }

        foreach ($this->options as $option => $value) {
            if ($option == 'add-header') {
                foreach ($value as $header) {
                    $c .= '--'.$option.' '.$header.' ';
                }
            } else {
                $c .= '--'.$option.(!is_bool($value) ? ' '.$value : '').' ';
            }
        }

        $c .= '--no-playlist --print-json --ignore-config';

        return $c;
    }

    /**
     * Download.
     *
     * @param string $url Video URL to download
     *
     * @throws PrivateVideoException
     * @throws CopyrightException
     * @throws NotFoundException
     * @throws ProcessFailedException
     * @throws \Exception
     *
     * @return Entity\Video[]|Entity\Video
     */
    public function download($url)
    {
        if (is_array($url)) {
            trigger_error('Providing multiple urls to download is deprecated and will be removed in version 1.0. Please call this method for each video alone.', E_USER_NOTICE);

            $videos = [];

            foreach ($url as $link) {
                $videos[] = $this->download($link);
            }

            return $videos;
        }

        if (!$this->isUrlSupported($url)) {
            throw new UrlNotSupportedException(sprintf('Provided url "%s" is not supported.', $url));
        }

        $process = $this->createProcess($this->buildCmdArgs($url));

        try {
            $process->mustRun(is_callable($this->debug) ? $this->debug : null);
        } catch (\Exception $e) {
            throw $this->handleException($e);
        }

        return $this->processDownloadOutput($process->getOutput());
    }

    /**
     * Get supported extractors list.
     *
     * @return array
     */
    public function getExtractorsList()
    {
        $process = $this->createProcess(['--list-extractors']);
        $process->mustRun(is_callable($this->debug) ? $this->debug : null);

        return array_filter(explode("\n", $process->getOutput()));
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $options = [
            // General options
            'ignore-errors' => 'bool',
            'abort-on-error' => 'bool',
            'default-search' => 'string',
            // Network options
            'proxy' => 'string',
            'socket-timeout' => 'int',
            'source-address' => 'string',
            'force-ipv4' => 'bool',
            'force-ipv6' => 'bool',
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
            'write-info-json' => 'bool',
            'write-annotations' => 'bool',
            'load-info' => 'string',
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

        $resolver->setNormalizer('output', function (Options $options, $value) {
            return sprintf('%s', $value);
        });
    }

    /**
     * Decode json to an associative array.
     *
     * @param string $data
     *
     * @return array
     */
    protected function jsonDecode($data)
    {
        $decode = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException('Response can\'t be decoded: '.$data);
        }

        return $decode;
    }

    /**
     * @param $output
     *
     * @return bool|Video
     */
    protected function processDownloadOutput($output)
    {
        $videoData = $this->jsonDecode(trim($output));

        if (is_array($videoData)) {
            $downloadPath = $this->downloadPath ?: sys_get_temp_dir();
            $downloadedFilePath = rtrim($downloadPath, '/').'/';

            $originalFile = $videoData['_filename'];

            $filename = pathinfo($originalFile, PATHINFO_FILENAME);

            $searchExtension = '*';
            $globFlags = null;

            if (isset($this->options['extract-audio']) && $this->options['extract-audio'] == true) {
                $searchExtension = '{'.implode(',', $this->allowedAudioFormats).'}';
                $globFlags = GLOB_BRACE;
            }

            $searchPattern = $filename.'.'.$searchExtension;

            if ($downloadedFilePath !== '/') {
                if ($this->moveWithPhp) {
                    $searchPattern = sys_get_temp_dir().'/'.$searchPattern;
                } else {
                    $searchPattern = $downloadedFilePath.$searchPattern;
                }
            }
            // http://php.net/manual/en/function.glob.php#75752
            $searchPattern = str_replace('[', '[[]', $searchPattern);

            $foundFiles = glob($searchPattern, $globFlags);
            $audioFile = reset($foundFiles);

            $videoData['_filename'] = pathinfo($audioFile, PATHINFO_BASENAME);

            if ($this->moveWithPhp) {
                rename(sys_get_temp_dir().'/'.$videoData['_filename'], $downloadedFilePath.$videoData['_filename']);
            }

            $videoData['file'] = new \SplFileInfo($downloadedFilePath.$videoData['_filename']);

            return new Video($videoData);
        }

        return false;
    }

    protected function handleException(\Exception $e)
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
        }

        return $e;
    }

    private function isUrlSupported($url)
    {
        if (preg_match('#soundcloud.com/.+/sets.+#', $url)) {
            return false;
        }

        return true;
    }

    private function createProcess(array $arguments = [])
    {
        array_unshift($arguments, $this->binPath ?: 'youtube-dl');

        $process = new Process($arguments);
        $process->setEnv(['LANG' => 'en_US.UTF-8']);
        $process->setTimeout($this->timeout);
        $process->setOptions($this->processOptions);

        if ($this->moveWithPhp) {
            $cwd = sys_get_temp_dir();
        } else {
            $cwd = $this->downloadPath ?: sys_get_temp_dir();
        }

        $process->setWorkingDirectory($cwd);

        return $process;
    }

    private function buildCmdArgs($url)
    {
        $arguments = [
            $url,
            '--no-playlist',
            '--print-json',
            '--ignore-config',
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

        return $arguments;
    }
}
