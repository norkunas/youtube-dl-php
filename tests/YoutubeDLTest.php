<?php
namespace YoutubeDL\Tests;

use YoutubeDL\YoutubeDL;
/**
 * @covers YoutubeDL\YoutubeDL
 */
class YoutubeDLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setters and getters
     */
    public function testSetGet()
    {
        $obj = new YoutubeDL();

        $obj->setWorkingDirectory('/home/user/');
        $obj->setTimeout(10);
        $obj->setProcessOptions(['suppress_errors' => false, 'binary_pipes' => false]);
        $obj->setDebug(true);

        $this->assertEquals('/home/user/', $obj->getWorkingDirectory());
        $this->assertEquals(10, $obj->getTimeout());
        $this->assertEquals(['suppress_errors' => false, 'binary_pipes' => false], $obj->getProcessOptions());
        $this->assertTrue($obj->getDebug());
    }

    public function testGetCommandLine()
    {
        $obj = new YoutubeDL([
            'skip-download' => true,
            'write-sub' => true,
            'write-annotations' => true,
            'audio-format' => 'mp3',
            'add-header' => [
                'X-Requested-With:youtube-dl',
                'X-ATT-DeviceId:GT-P7320/P7320XXLPG'
            ]
        ]);

        $this->assertEquals('youtube-dl --skip-download --write-sub --write-annotations --audio-format mp3 --add-header X-Requested-With:youtube-dl --add-header X-ATT-DeviceId:GT-P7320/P7320XXLPG --print-json', $obj->getCommandLine());
    }

    public function testDownload()
    {
        $obj = new YoutubeDL(['skip-download' => true]);

        $this->assertInstanceOf('YoutubeDL\Entity\Video', $obj->download('https://www.youtube.com/watch?v=BaW_jenozKc'));
    }
}
