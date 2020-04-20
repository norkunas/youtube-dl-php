<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Exception\UrlNotSupportedException;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Entity\Video;

class YoutubeDlTest extends TestCase
{
    public function testDownloadPathNotSet()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No download path was set.');

        (new YoutubeDl())->download('');
    }

    public function testGetInfo()
    {
        $yt = $this->getMockBuilder(YoutubeDl::class)
            ->disableOriginalConstructor()
            ->setMethods(['getInfo'])
            ->getMock();

        $this->assertInstanceOf(Video::class, $yt->getInfo('https://www.youtube.com/watch?v=aqz-KE-bpKQ'));
    }

    public function testGetExtractorsList()
    {
        $yt = $this->getMockBuilder(YoutubeDl::class)
            ->disableOriginalConstructor()
            ->setMethods(['getExtractorsList'])
            ->getMock();
        $yt->expects($this->once())
            ->method('getExtractorsList')
            ->willReturn(['youtube']);

        $this->assertGreaterThanOrEqual(1, $yt->getExtractorsList());
    }

    public function testUrlNotSupported()
    {
        $this->expectException(UrlNotSupportedException::class);
        $this->expectExceptionMessageMatches('/Provided url \'.+\' is not supported\./');

        $yt = new YoutubeDl();
        $yt->setDownloadPath('/');
        $yt->download('https://soundcloud.com/csimpi/sets/go4it-demo');
    }
}
