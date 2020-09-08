<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Metadata;

use JsonException;
use PHPUnit\Framework\TestCase;
use YoutubeDl\Exception\FileException;
use YoutubeDl\Metadata\DefaultMetadataReader;

final class DefaultMetadataReaderTest extends TestCase
{
    private DefaultMetadataReader $reader;

    protected function setUp(): void
    {
        $this->reader = new DefaultMetadataReader();
    }

    public function testValidJson(): void
    {
        self::assertSame([
            'name' => 'YoutubeDl',
        ], $this->reader->read(__DIR__.'/../Fixtures/valid.json'));
    }

    public function testInvalidJson(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $this->reader->read(__DIR__.'/../Fixtures/invalid.json');
    }

    public function testThrowsIfCannotReadFile(): void
    {
        $file = __DIR__.'/../Fixtures/non_existing_file.txt';

        $this->expectException(FileException::class);
        $this->expectExceptionMessage('Cannot read "'.$file.'" file.');

        @$this->reader->read($file);
    }
}
