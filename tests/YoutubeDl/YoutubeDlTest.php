<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Exception\UrlNotSupportedException;
use YoutubeDl\YoutubeDl;

class YoutubeDlTest extends TestCase
{
    public function testDownloadPathNotSet()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No download path was set.');

        (new YoutubeDl())->download('');
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
        $this->expectExceptionMessageRegExp('/Provided url \'.+\' is not supported\./');

        $yt = new YoutubeDl();
        $yt->setDownloadPath('/');
        $yt->download('https://soundcloud.com/csimpi/sets/go4it-demo');
    }
}
