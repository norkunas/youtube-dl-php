<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use RuntimeException;
use Symfony\Component\Process\Process;
use YoutubeDl\Exception\FileException;
use function copy;
use function file;
use function file_get_contents;
use function is_callable;

class StaticProcess extends Process
{
    private ?string $outputFile = null;
    private ?array $writeMetadata = null;

    public function __construct()
    {
        // noop
    }

    public function setOutputFile(string $outputFile): void
    {
        $this->outputFile = $outputFile;
    }

    /**
     * @param array<int, array{from:string, to:string}> $writeMetadata
     */
    public function writeMetadata(array $writeMetadata): void
    {
        $this->writeMetadata = $writeMetadata;
    }

    public function run(callable $callback = null, array $env = []): int
    {
        if (!is_callable($callback)) {
            throw new RuntimeException(sprintf('`Callable` must be provided to "%s::%s".', __CLASS__, __METHOD__));
        }

        if ($this->outputFile === null) {
            throw new RuntimeException(sprintf('Output file must be set for "%s::%s".', __CLASS__, __METHOD__));
        }

        $output = file($this->outputFile);

        if ($output === false) {
            throw FileException::cannotRead($this->outputFile);
        }

        assert(is_callable($callback));
        foreach ($output as $line) {
            $callback('', $line);
        }

        if ($this->writeMetadata !== null) {
            foreach ($this->writeMetadata as $metadata) {
                copy($metadata['from'], $metadata['to']);
            }
        }

        return 0;
    }

    public function mustRun(callable $callback = null, array $env = []): Process
    {
        return $this;
    }

    public function getOutput(): string
    {
        if ($this->outputFile === null) {
            throw new RuntimeException(sprintf('Output file must be set for "%s::%s".', __CLASS__, __METHOD__));
        }

        $output = file_get_contents($this->outputFile);

        if ($output === false) {
            throw FileException::cannotRead($this->outputFile);
        }

        return $output;
    }
}
