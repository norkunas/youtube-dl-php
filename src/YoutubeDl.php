<?php

declare(strict_types=1);

namespace YoutubeDl;

use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use YoutubeDl\Entity\Extractor;
use YoutubeDl\Entity\Mso;
use YoutubeDl\Entity\SubListItem;
use YoutubeDl\Entity\Thumbnail;
use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;
use YoutubeDl\Exception\MsoNotParsableException;
use YoutubeDl\Exception\NoDownloadPathProvidedException;
use YoutubeDl\Exception\NoUrlProvidedException;
use YoutubeDl\Metadata\DefaultMetadataReader;
use YoutubeDl\Metadata\MetadataReaderInterface;
use YoutubeDl\Process\ArgvBuilder;
use YoutubeDl\Process\DefaultProcessBuilder;
use YoutubeDl\Process\ProcessBuilderInterface;
use YoutubeDl\Process\TableParser;

use const PREG_SET_ORDER;

use function basename;
use function count;
use function explode;
use function in_array;
use function preg_match;
use function str_starts_with;
use function strpos;
use function substr;
use function trim;

class YoutubeDl
{
    public const PROGRESS_PATTERN = '#\[download\]\s+(?<percentage>\d+(?:\.\d+)?%)\s+of\s+(?<size>[~]?\d+(?:\.\d+)?(?:K|M|G)iB)(?:\s+at\s+(?<speed>(\d+(?:\.\d+)?(?:K|M|G)iB/s)|Unknown speed))?(?:\s+ETA\s+(?<eta>([\d:]{2,8}|Unknown ETA)))?(\s+in\s+(?<totalTime>[\d:]{2,8}))?#i';

    private ProcessBuilderInterface $processBuilder;
    private MetadataReaderInterface $metadataReader;
    private Filesystem $filesystem;

    /**
     * @var non-empty-string|null
     */
    private ?string $binPath = null;

    /**
     * @var non-empty-string|null
     */
    private ?string $pythonPath = null;

    /**
     * @var callable
     */
    private $progress;

    /**
     * @var callable
     */
    private $debug;

    public function __construct(?ProcessBuilderInterface $processBuilder = null, ?MetadataReaderInterface $metadataReader = null, ?Filesystem $filesystem = null)
    {
        $this->processBuilder = $processBuilder ?? new DefaultProcessBuilder();
        $this->metadataReader = $metadataReader ?? new DefaultMetadataReader();
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->progress = static function (?string $progressTarget, string $percentage, string $size, ?string $speed, ?string $eta, ?string $totalTime): void {};
        $this->debug = static function (string $type, string $buffer): void {};
    }

    /**
     * @param non-empty-string|null $binPath
     */
    public function setBinPath(?string $binPath): self
    {
        $this->binPath = $binPath;

        return $this;
    }

    /**
     * @param non-empty-string|null $pythonPath
     */
    public function setPythonPath(?string $pythonPath): self
    {
        $this->pythonPath = $pythonPath;

        return $this;
    }

    public function onProgress(callable $onProgress): self
    {
        $this->progress = $onProgress;

        return $this;
    }

    public function debug(callable $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    public function download(Options $options): VideoCollection
    {
        $urls = $options->getUrl();

        if (count($urls) === 0) {
            throw new NoUrlProvidedException('Missing configured URL to download.');
        }

        if ($options->getDownloadPath() === null) {
            throw new NoDownloadPathProvidedException('Missing configured downloadPath option.');
        }

        $arguments = [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            ...ArgvBuilder::build($options),
        ];

        $parsedData = [];
        $currentVideo = null;
        $progressTarget = null;

        $process = $this->processBuilder->build($this->binPath, $this->pythonPath, $arguments);
        $process->run(function (string $type, string $buffer) use (&$currentVideo, &$parsedData, &$progressTarget): void {
            ($this->debug)($type, $buffer);

            if (preg_match('/\[(?<extractor>.+)]\s(?<id>.+):\sDownloading (pc )?webpage/', $buffer, $match) === 1) {
                if ($currentVideo !== null) {
                    $parsedData[] = $currentVideo;
                    $currentVideo = null;
                }

                $currentVideo['extractor'] = $match['extractor'];
                $currentVideo['id'] = $match['id'];
            }

            if (preg_match('/\[download] File is (larger|smaller) than (min|max)-filesize/', $buffer, $match) === 1) {
                $currentVideo['error'] = trim(substr($buffer, 11));
            } elseif (str_starts_with($buffer, 'ERROR:')) {
                $currentVideo['error'] = trim(substr($buffer, 6));
            }

            if (preg_match('/Writing video( description)? metadata as JSON to:\s(?<metadataFile>.+)/', $buffer, $match) === 1) {
                $currentVideo['metadataFile'] = $match['metadataFile'];
            }

            if (preg_match('/\[(ffmpeg|Merger)] Merging formats into "(?<file>.+)"/', $buffer, $match) === 1) {
                $currentVideo['fileName'] = $match['file'];
            } elseif (preg_match('/\[(download|ffmpeg|ExtractAudio)] Destination: (?<file>.+)/', $buffer, $match) === 1 || preg_match('/\[download] (?<file>.+) has already been downloaded/', $buffer, $match) === 1) {
                $currentVideo['fileName'] = $match['file'];
                $progressTarget = basename($match['file']);
            }

            if (preg_match_all(static::PROGRESS_PATTERN, $buffer, $matches, PREG_SET_ORDER) !== false) {
                if (count($matches) > 0) {
                    $progress = $this->progress;

                    foreach ($matches as $progressMatch) {
                        $progress($progressTarget, $progressMatch['percentage'], $progressMatch['size'], $progressMatch['speed'] ?? null, $progressMatch['eta'] ?? null, $progressMatch['totalTime'] ?? null);
                    }
                }
            }
        });

        if ($currentVideo !== null && !in_array($currentVideo, $parsedData, true)) {
            $parsedData[] = $currentVideo;
        }

        $videos = [];
        $metadataFiles = [];

        foreach ($parsedData as $parsedRow) {
            if (isset($parsedRow['error'])) {
                $videos[] = new Video([
                    'error' => $parsedRow['error'],
                    'extractor' => $parsedRow['extractor'] ?? 'generic',
                ]);
            } elseif (isset($parsedRow['metadataFile'])) {
                $metadataFile = $parsedRow['metadataFile'];
                $metadataFiles[] = $metadataFile;
                $metadata = $this->metadataReader->read($metadataFile);
                if (isset($parsedRow['fileName'])) {
                    $metadata['_filename'] = $parsedRow['fileName'];
                    $metadata['file'] = new SplFileInfo($metadata['_filename']);
                }
                $metadata['metadataFile'] = new SplFileInfo($metadataFile);

                $videos[] = new Video($metadata);
            }
        }

        if ($options->getCleanupMetadata()) {
            $this->filesystem->remove($metadataFiles);
        }

        return new VideoCollection($videos);
    }

    /**
     * @param non-empty-string $url
     *
     * @return list<Thumbnail>
     */
    public function listThumbnails(string $url): array
    {
        $process = $this->processBuilder->build($this->binPath, $this->pythonPath, ['--list-thumbnails', $url]);
        $process->mustRun();

        $data = explode("\n", $process->getOutput());
        $parsing = null;
        $header = '';
        $rows = [];

        foreach ($data as $line) {
            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, '[info] Thumbnails for')) {
                $parsing = 'table_header';
            } elseif ($parsing === 'table_header') {
                $header = $line;
                $parsing = 'thumbnails';
            } elseif ($parsing === 'thumbnails') {
                $rows[] = $line;
            }
        }

        return array_map(static fn (array $row) => new Thumbnail($row), TableParser::parse($header, $rows));
    }

    /**
     * @return SubListItem[]
     */
    public function listSubs(string $url): array
    {
        $process = $this->processBuilder->build($this->binPath, $this->pythonPath, ['--list-subs', $url]);
        $process->mustRun();

        $data = explode("\n", $process->getOutput());
        $parsing = null;
        $header = '';
        $autoCaptionRows = [];
        $subtitleRows = [];

        foreach ($data as $line) {
            if ($line === '') {
                continue;
            }

            if (str_contains($line, 'Available automatic captions for')) {
                $parsing = 'auto_caption';
            } elseif (str_contains($line, 'Available subtitles for')) {
                $parsing = 'subtitles';
            } elseif (str_contains($line, 'has no automatic captions') || str_contains($line, 'has no subtitles')) {
                $parsing = null;
            } elseif (str_contains($line, 'Language')) {
                $header = $line;
            } elseif ($parsing !== null) {
                if ($parsing === 'auto_caption') {
                    $autoCaptionRows[] = $line;
                } else {
                    $subtitleRows[] = $line;
                }
            }
        }

        $list = [];
        foreach (TableParser::parse($header, $autoCaptionRows) as $row) {
            $list[] = new SubListItem([
                'language' => $row['language'],
                'formats' => explode(', ', $row['formats']),
                'auto_caption' => true,
            ]);
        }

        foreach (TableParser::parse($header, $subtitleRows) as $row) {
            $list[] = new SubListItem([
                'language' => $row['language'],
                'formats' => explode(', ', $row['formats']),
                'auto_caption' => false,
            ]);
        }

        return $list;
    }

    /**
     * @return Extractor[]
     */
    public function getExtractorsList(): array
    {
        $process = $this->processBuilder->build($this->binPath, $this->pythonPath, ['--list-extractors']);
        $process->mustRun();

        return array_map(static fn (string $extractor) => new Extractor(['title' => $extractor]), array_filter(explode("\n", $process->getOutput())));
    }

    /**
     * @todo: use TableParser
     *
     * @return Mso[]
     */
    public function getMultipleSystemOperatorsList(): array
    {
        $process = $this->processBuilder->build($this->binPath, $this->pythonPath, ['--ap-list-mso']);
        $process->mustRun();

        $output = explode("\n", $process->getOutput());

        if (count($output) <= 2) {
            return [];
        }

        unset($output[0]); // Remove "Supported TV Providers:" line
        // Calculate how much space "mso" takes on the line
        $msoWidth = strpos($output[1], 'mso name');
        if ($msoWidth === false) {
            throw new MsoNotParsableException('Cannot properly parse Multiple System Operators list');
        }
        unset($output[1]);

        $list = [];

        foreach ($output as $line) {
            if ($line === '') {
                continue;
            }

            $code = trim(substr($line, 0, $msoWidth));
            $name = substr($line, $msoWidth);

            $list[] = new Mso(['code' => $code, 'name' => $name]);
        }

        return $list;
    }
}
