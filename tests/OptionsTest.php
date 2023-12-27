<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Exception\InvalidArgumentException;
use YoutubeDl\Options;

final class OptionsTest extends TestCase
{
    public function testWithWindowsFilenames(): void
    {
        $options = Options::create()->windowsFilenames(true)->toArray();

        self::assertArrayHasKey('windows-filenames', $options);
    }

    public function testInvalidMergeOutputFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `mergeOutputFormat` expected one of: "mkv", "mp4", "ogg", "webm", "flv". Got: "mp4000".');

        Options::create()->mergeOutputFormat('mp4000');
    }

    public function testInvalidAudioFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `audioFormat` expected one of: "best", "aac", "flac", "mp3", "m4a", "opus", "vorbis", "wav", "alac". Got: "mp3000".');

        Options::create()->audioFormat('mp3000'); // @phpstan-ignore-line
    }

    public function testInvalidRecodeVideoThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `recodeVideo` expected one of: "avi", "flv", "gif", "mkv", "mov", "mp4", "webm", "aac", "aiff", "alac", "flac", "m4a", "mka", "mp3", "ogg", "opus", "vorbis", "wav". Got: "mp4000".');

        Options::create()->recodeVideo('mp4000');
    }

    public function testInvalidConvertSubsFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `convertSubsFormat` expected one of: "srt", "vtt", "ass", "lrc". Got: "usf".');

        Options::create()->convertSubsFormat('usf'); // @phpstan-ignore-line
    }

    public function testAuthenticateThrowsWithoutUsername(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Authentication username and password must be provided when configuring account details.');

        Options::create()->authenticate(null, 'password');
    }

    public function testAuthenticateThrowsWithoutPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Authentication username and password must be provided when configuring account details.');

        Options::create()->authenticate('username', null);
    }

    public function testOutputThrowsWithDirectorySeparator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Providing download path via `output` option is prohibited. Set the download path when creating Options object or calling `downloadPath` method.');

        Options::create()->output('/var/downloads');
    }
}
