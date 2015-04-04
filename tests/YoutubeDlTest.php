<?php
namespace YoutubeDl\Tests;

use YoutubeDl\YoutubeDl;
/**
 * @covers YoutubeDl\YoutubeDl
 */
class YoutubeDlTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCommandLine()
    {
        $obj = new YoutubeDl([
            'skip-download' => true,
            'write-sub' => true,
            'write-annotations' => true,
            'audio-format' => 'mp3',
            'add-header' => [
                'X-Requested-With:youtube-dl',
                'X-ATT-DeviceId:GT-P7320/P7320XXLPG'
            ]
        ]);

        $this->assertEquals('youtube-dl --skip-download --write-sub --write-annotations --audio-format mp3 --add-header X-Requested-With:youtube-dl --add-header X-ATT-DeviceId:GT-P7320/P7320XXLPG --print-json --ignore-config', $obj->getCommandLine());
    }

    public function testYoutubeDownload()
    {
        $obj = new YoutubeDl(['skip-download' => true]);

        $this->assertInstanceOf('YoutubeDl\Entity\Video', $obj->download('https://www.youtube.com/watch?v=BaW_jenozKc'));
    }
    /**
     * @expectedException \YoutubeDl\Exception\NotFoundException
     */
    public function testYoutubeBadDownload()
    {
        $obj = new YoutubeDl(['skip-download' => true]);
        $obj->download('https://www.youtube.com/watch?v=togdRwApGvs');
    }
    /**
     * @expectedException \YoutubeDl\Exception\PrivateVideoException
     */
    public function testYoutubePrivateVideoDownload()
    {
        $obj = new YoutubeDl(['skip-download' => true]);
        $obj->download('https://www.youtube.com/watch?v=6pbDgvC31E4');
    }
    /**
     * @expectedException \YoutubeDl\Exception\CopyrightException
     */
    public function testYoutubeRemovedVideoDownload()
    {
        $obj = new YoutubeDl(['skip-download' => true]);
        $obj->download('https://www.youtube.com/watch?v=AYeiLa_F8fk');
    }
}
