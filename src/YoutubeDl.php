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

class YoutubeDl
{
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

    public function download(string $url): Video
    {
        if (!$this->downloadPath) {
            throw new \RuntimeException('No download path was set.');
        }

        if (!$this->isUrlSupported($url)) {
            throw new UrlNotSupportedException(sprintf('Provided url "%s" is not supported.', $url));
        }

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

        $process = $this->createProcess($arguments);

        try {
            $process->mustRun(is_callable($this->debug) ? $this->debug : null);
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

    private function jsonDecode($data): array
    {
        $decoded = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Response can\'t be decoded: %s.', $data));
        }

        return $decoded;
    }

    private function processDownload(Process $process): Video
    {
        $videoData = $this->jsonDecode(trim($process->getOutput()));

        if (isset($this->options['extract-audio']) && true === $this->options['extract-audio']) {
            $file = $this->findFile($videoData['_filename'], implode('|', $this->allowedAudioFormats));
            $videoData['_filename'] = pathinfo($file, PATHINFO_BASENAME);
        } elseif (preg_match('/merged into mkv/', $process->getErrorOutput())) {
            $videoData['_filename'] = pathinfo($this->findFile($videoData['_filename'], 'mkv'), PATHINFO_BASENAME);
        }

        $videoData['file'] = new \SplFileInfo(rtrim($this->downloadPath, '/').'/'.$videoData['_filename']);

        return new Video($videoData);
    }

    private function handleException(\Exception $e): \Exception
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

    private function createProcess(array $arguments = []): Process
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

    private function findFile(string $fileName, string $extension)
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
    }

    private function isUrlSupported(string $url): bool
    {
        if (preg_match('#soundcloud.com/.+/sets.+#', $url)) {
            return false;
        }

        return true;
    }
}
