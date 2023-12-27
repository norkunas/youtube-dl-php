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

    /**
     * @var list<array{from: non-empty-string, to: non-empty-string}>|null
     */
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
     * @param list<array{from: non-empty-string, to: non-empty-string}> $writeMetadata
     */
    public function writeMetadata(array $writeMetadata): void
    {
        $this->writeMetadata = $writeMetadata;
    }

    /**
     * @param array<mixed> $env
     */
    public function start(callable $callback = null, array $env = []): void
    {
        if (!is_callable($callback) || $this->outputFile === null) {
            return;
        }

        $output = file($this->outputFile);

        if ($output === false) {
            throw FileException::cannotRead($this->outputFile);
        }

        foreach ($output as $line) {
            $callback('', $line);
        }

        if ($this->writeMetadata !== null) {
            foreach ($this->writeMetadata as $metadata) {
                copy($metadata['from'], $metadata['to']);
            }
        }
    }

    public function wait(callable $callback = null): int
    {
        return 0;
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
