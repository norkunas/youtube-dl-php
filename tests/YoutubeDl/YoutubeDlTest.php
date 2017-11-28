<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use PHPUnit\Framework\TestCase;
use YoutubeDl\YoutubeDl;

class YoutubeDlTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testDownloadPathNotSet()
    {
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

    /**
     * @expectedException \YoutubeDl\Exception\UrlNotSupportedException
     * @expectedExceptionMessageRegExp /Provided url ".+" is not supported\./
     */
    public function testUrlNotSupported()
    {
        $yt = new YoutubeDl();
        $yt->setDownloadPath('/');
        $yt->download('https://soundcloud.com/csimpi/sets/go4it-demo');
    }
}
