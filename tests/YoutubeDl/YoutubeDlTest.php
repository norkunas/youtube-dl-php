<?php

namespace YoutubeDl\Tests;

use Symfony\Component\Process\ExecutableFinder;
use YoutubeDl\YoutubeDl;

class YoutubeDlTest extends \PHPUnit_Framework_TestCase
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
        $yt = new YoutubeDl();
        $extractors = $yt->getExtractorsList();

        $this->assertInternalType('array', $extractors);
        $this->assertGreaterThanOrEqual(1, count($extractors));
    }

    public function testVideoDownload()
    {
        $yt = new YoutubeDl();
        $yt->setDownloadPath(dirname(__DIR__));

        $video = $yt->download('https://www.youtube.com/watch?v=voYxuPHs_kg');

        $this->assertTrue($video->getFile()->isFile());
        $this->assertSame('webm', $video->getFile()->getExtension());

        @unlink($video->getFile()->getPathname());
    }

    public function testAudioDownload()
    {
        $this->skipIfNoPostProcessor();

        $yt = new YoutubeDl([
            'extract-audio' => true,
            'audio-format' => 'mp3',
        ]);
        $yt->setDownloadPath(dirname(__DIR__));

        $audio = $yt->download('https://www.youtube.com/watch?v=TDaodHP-NI4');

        $this->assertTrue($audio->getFile()->isFile());
        $this->assertSame('mp3', $audio->getFile()->getExtension());

        @unlink($audio->getFile()->getPathname());
    }

    /**
     * @expectedException \YoutubeDl\Exception\NotFoundException
     */
    public function testYoutubeBadDownload()
    {
        $yt = new YoutubeDl(['skip-download' => true]);
        $yt->setDownloadPath('/');
        $yt->download('https://www.youtube.com/watch?v=togdRwApGvs');
    }

    /**
     * @expectedException \YoutubeDl\Exception\PrivateVideoException
     */
    public function testYoutubePrivateVideoDownload()
    {
        $yt = new YoutubeDl(['skip-download' => true]);
        $yt->setDownloadPath('/');
        $yt->download('https://www.youtube.com/watch?v=6pbDgvC31E4');
    }

    /**
     * @expectedException \YoutubeDl\Exception\CopyrightException
     */
    public function testYoutubeRemovedVideoDownload()
    {
        $yt = new YoutubeDl(['skip-download' => true]);
        $yt->setDownloadPath('/');
        $yt->download('https://www.youtube.com/watch?v=AYeiLa_F8fk');
    }

    /**
     * @expectedException \YoutubeDl\Exception\AccountTerminatedException
     */
    public function testYoutubeAccountTerminatedVideoDownload()
    {
        $yt = new YoutubeDl(['skip-download' => true]);
        $yt->setDownloadPath('/');
        $yt->download('https://www.youtube.com/watch?v=oIdgb-vwAQI');
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

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function testBadUrlVideoDownload()
    {
        $yt = new YoutubeDl(['skip-download' => true]);
        $yt->setDownloadPath('/');
        $yt->download('https://www.example.com');
    }

    private function skipIfNoPostProcessor()
    {
        $ffmpeg = (new ExecutableFinder())->find('ffmpeg');
        $ffprobe = (new ExecutableFinder())->find('ffprobe');
        $avconv = (new ExecutableFinder())->find('avconv');
        $avprobe = (new ExecutableFinder())->find('avprobe');

        if (null === $ffmpeg && null === $ffprobe && null === $avconv && null === $avprobe) {
            $this->markTestSkipped();
        }
    }
}
