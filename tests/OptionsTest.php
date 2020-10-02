<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Exception\InvalidArgumentException;
use YoutubeDl\Options;

final class OptionsTest extends TestCase
{
    public function testInvalidMergeOutputFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `mergeOutputFormat` expected one of: "mkv", "mp4", "ogg", "webm", "flv". Got: "mp4000".');

        Options::create()->mergeOutputFormat('mp4000');
    }

    public function testInvalidAudioFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `audioFormat` expected one of: "best", "aac", "vorbis", "mp3", "m4a", "opus", "wav". Got: "mp3000".');

        Options::create()->audioFormat('mp3000');
    }

    public function testInvalidRecodeVideoThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `recodeVideo` expected one of: "mp4", "flv", "ogg", "webm", "mkv", "avi". Got: "mp4000".');

        Options::create()->recodeVideo('mp4000');
    }

    public function testInvalidConvertSubsFormatThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Option `convertSubsFormat` expected one of: "srt", "ass", "vtt", "lrc". Got: "usf".');

        Options::create()->convertSubsFormat('usf');
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
}
