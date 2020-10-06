<?php

declare(strict_types=1);

namespace YoutubeDl\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Process\Process;
use YoutubeDl\Entity\Extractor;
use YoutubeDl\Entity\Mso;
use YoutubeDl\Entity\SubListItem;
use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;
use YoutubeDl\Exception\FileException;
use YoutubeDl\Exception\NoDownloadPathProvidedException;
use YoutubeDl\Exception\NoUrlProvidedException;
use YoutubeDl\Options;
use YoutubeDl\Process\ProcessBuilderInterface;
use YoutubeDl\YoutubeDl;
use const JSON_THROW_ON_ERROR;
use function basename;
use function file_get_contents;
use function json_decode;

class YoutubeDlTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('yt-dl'));

        $this->tmpDir = vfsStream::url('yt-dl');
    }

    protected function tearDown(): void
    {
        vfsStreamWrapper::unregister();
    }

    public function testDownloadWithoutUrl(): void
    {
        $this->expectException(NoUrlProvidedException::class);
        $this->expectExceptionMessage('Missing configured URL to download.');

        $yt = new YoutubeDl();
        $yt->download(Options::create()->downloadPath($this->tmpDir));
    }

    public function testDownloadWithoutDownloadPath(): void
    {
        $this->expectException(NoDownloadPathProvidedException::class);
        $this->expectExceptionMessage('Missing configured downloadPath option.');

        $yt = new YoutubeDl();
        $yt->download(Options::create()->url('https://url'));
    }

    /**
     * @dataProvider provideSimpleVideoCases
     */
    public function testDownloadSimpleVideo(string $url, string $outputFile, string $metadataFile): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);
        $process->writeMetadata([
            ['from' => $metadataFile, 'to' => $this->tmpDir.'/'.basename($metadataFile)]
        ]);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $video = new Video($m = $this->readJsonFile($metadataFile));
        $video['file'] = new SplFileInfo($this->tmpDir.'/'.basename($m['_filename']));
        $video['metadataFile'] = new SplFileInfo($this->tmpDir.'/'.basename($metadataFile));

        self::assertEquals(new VideoCollection([$video]), $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    /**
     * @dataProvider provideDownloadPlaylistCases
     */
    public function testDownloadPlaylist(string $url, string $outputFile, array $metadataFiles): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);
        $process->writeMetadata(array_map(function (string $metadataFile) {
            return ['from' => $metadataFile, 'to' => $this->tmpDir.'/'.basename($metadataFile)];
        }, $metadataFiles));

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $videos = array_map(function (string $metadataFile) {
            $video = new Video($m = $this->readJsonFile($metadataFile));
            $video['file'] = new SplFileInfo($this->tmpDir.'/'.basename($m['_filename']));
            $video['metadataFile'] = new SplFileInfo($this->tmpDir.'/'.basename($metadataFile));

            return $video;
        }, $metadataFiles);

        self::assertEquals(new VideoCollection($videos), $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    /**
     * @dataProvider provideDownloadPlaylistMatchTitleCases
     */
    public function testDownloadPlaylistMatchTitle(string $url, string $outputFile, VideoCollection $expectedCollection): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--match-title=abc',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        self::assertEquals($expectedCollection, $yt->download(Options::create()->downloadPath($this->tmpDir)->matchTitle('abc')->url($url)));
    }

    /**
     * @dataProvider provideDownloadPlaylistRejectTitleCases
     */
    public function testDownloadPlaylistRejectTitle(string $url, string $outputFile, VideoCollection $expectedCollection): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--reject-title=sh',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        self::assertEquals($expectedCollection, $yt->download(Options::create()->downloadPath($this->tmpDir)->rejectTitle('sh')->url($url)));
    }

    public function testOnProgress(): void
    {
        $process = new StaticProcess();
        $process->setOutputFile(__DIR__.'/Fixtures/progress.txt');
        $process->writeMetadata([
            [
                'from' => $metadataFile = __DIR__.'/Fixtures/youtube/Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.info.json',
                'to' => $this->tmpDir.'/'.basename($metadataFile),
            ],
        ]);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url = 'https://www.youtube.com/watch?v=2Hy4bT0ESfc',
        ]);

        $calls = [];

        $yt = new YoutubeDl($processBuilder);
        $yt->onProgress(static function (string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime) use (&$calls) {
            $calls[] = [$progressTarget, $percentage, $size, $speed, $eta, $totalTime];
        });

        $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url));

        static::assertSame([
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.0%', '21.01MiB', '474.63KiB/s', '00:45', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.0%', '21.01MiB', '1.24MiB/s', '00:17', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.0%', '21.01MiB', '2.70MiB/s', '00:07', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.1%', '21.01MiB', '5.46MiB/s', '00:03', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.1%', '21.01MiB', '3.83MiB/s', '00:05', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.3%', '21.01MiB', '4.25MiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '0.6%', '21.01MiB', '4.43MiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '1.2%', '21.01MiB', '4.35MiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '2.4%', '21.01MiB', '4.67MiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '4.8%', '21.01MiB', '5.00MiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '9.5%', '21.01MiB', '5.14MiB/s', '00:03', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '19.0%', '21.01MiB', '5.34MiB/s', '00:03', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '38.1%', '21.01MiB', '5.33MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.3%', '21.01MiB', '5.39MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.4%', '21.01MiB', 'Unknown speed', 'Unknown ETA', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.4%', '21.01MiB', 'Unknown speed', 'Unknown ETA', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.4%', '21.01MiB', 'Unknown speed', 'Unknown ETA', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.4%', '21.01MiB', 'Unknown speed', 'Unknown ETA', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.5%', '21.01MiB', '3.29MiB/s', '00:03', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.6%', '21.01MiB', '3.80MiB/s', '00:03', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '45.9%', '21.01MiB', '4.20MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '46.5%', '21.01MiB', '4.97MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '47.7%', '21.01MiB', '5.06MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '50.1%', '21.01MiB', '5.10MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '54.9%', '21.01MiB', '5.12MiB/s', '00:01', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '64.4%', '21.01MiB', '4.98MiB/s', '00:01', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '83.4%', '21.01MiB', '5.05MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.2%', '21.01MiB', '5.07MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.2%', '21.01MiB', '412.38KiB/s', '00:04', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.2%', '21.01MiB', '1.14MiB/s', '00:01', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.3%', '21.01MiB', '2.59MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.3%', '21.01MiB', '5.41MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.4%', '21.01MiB', '3.14MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.5%', '21.01MiB', '3.82MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '92.8%', '21.01MiB', '4.49MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '93.4%', '21.01MiB', '4.29MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '94.6%', '21.01MiB', '4.57MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '97.0%', '21.01MiB', '4.68MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '100.0%', '21.01MiB', '4.99MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f136.mp4', '100%', '21.01MiB', '', '', '00:04'],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '0.0%', '3.52MiB', '231.69KiB/s', '00:15', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '0.1%', '3.52MiB', '643.50KiB/s', '00:05', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '0.2%', '3.52MiB', '1.38MiB/s', '00:02', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '0.4%', '3.52MiB', '2.82MiB/s', '00:01', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '0.9%', '3.52MiB', '5.17MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '1.8%', '3.52MiB', '4.86MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '3.5%', '3.52MiB', '4.53MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '7.1%', '3.52MiB', '4.50MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '14.2%', '3.52MiB', '4.78MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '28.4%', '3.52MiB', '4.96MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '56.9%', '3.52MiB', '5.22MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '100.0%', '3.52MiB', '5.08MiB/s', '00:00', null],
            ['Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.f251.webm', '100%', '3.52MiB', '', '', '00:00'],
        ], $calls);
    }

    /**
     * @dataProvider provideUnsupportedUrlCases
     */
    public function testDownloadUnsupportedUrl(string $url, string $outputFile, Video $expectedEntity): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = new VideoCollection([$expectedEntity]);

        self::assertEquals($collection, $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    /**
     * @dataProvider provideIncompleteUrlCases
     */
    public function testDownloadIncompleteUrl(string $url, string $outputFile, Video $expectedEntity): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = new VideoCollection([$expectedEntity]);

        self::assertEquals($collection, $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    /**
     * @dataProvider providePrivatePlaylistCases
     */
    public function testDownloadPrivatePlaylist(string $url, string $outputFile, Video $expectedEntity): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = new VideoCollection([$expectedEntity]);

        self::assertEquals($collection, $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    /**
     * @dataProvider provideUnreachableNetworkCases
     */
    public function testDownloadWhenNetworkIsUnreachable(string $url, string $outputFile, Video $expectedEntity): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = new VideoCollection([$expectedEntity]);

        self::assertEquals($collection, $yt->download(Options::create()->downloadPath($this->tmpDir)->url($url)));
    }

    public function testDownloadWithMetadataCleanup(): void
    {
        $process = new StaticProcess();
        $process->setOutputFile(__DIR__.'/Fixtures/youtube/batman_trailer_2021.txt');
        $process->writeMetadata([
            [
                'from' => $metadataFile = __DIR__.'/Fixtures/youtube/THE BATMAN Trailer (2021)--FZ-pPFAjYY.info.json',
                'to' => $this->tmpDir.'/'.basename($metadataFile),
            ],
        ]);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url = 'https://www.youtube.com/watch?v=-FZ-pPFAjYY',
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = $yt->download(Options::create()->cleanupMetadata(true)->downloadPath($this->tmpDir)->url($url));

        foreach ($collection->getVideos() as $video) {
            self::assertFileNotExists($video->getMetadataFile()->getPathname());
        }
    }

    public function testDownloadWithoutMetadataCleanup(): void
    {
        $process = new StaticProcess();
        $process->setOutputFile(__DIR__.'/Fixtures/youtube/batman_trailer_2021.txt');
        $process->writeMetadata([
            [
                'from' => $metadataFile = __DIR__.'/Fixtures/youtube/THE BATMAN Trailer (2021)--FZ-pPFAjYY.info.json',
                'to' => $this->tmpDir.'/'.basename($metadataFile),
            ],
        ]);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--ignore-config',
            '--ignore-errors',
            '--write-info-json',
            '--output=vfs://yt-dl/%(title)s-%(id)s.%(ext)s',
            $url = 'https://www.youtube.com/watch?v=-FZ-pPFAjYY',
        ]);

        $yt = new YoutubeDl($processBuilder);

        $collection = $yt->download(Options::create()->cleanupMetadata(false)->downloadPath($this->tmpDir)->url($url));

        foreach ($collection->getVideos() as $video) {
            self::assertFileExists($video->getMetadataFile()->getPathname());
        }
    }

    /**
     * @dataProvider provideListSubsCases
     */
    public function testListSubs(string $url, string $outputFile, array $expectedSubs): void
    {
        $process = new StaticProcess();
        $process->setOutputFile($outputFile);

        $processBuilder = $this->createProcessBuilderMock($process, [
            '--list-subs',
            $url,
        ]);

        $yt = new YoutubeDl($processBuilder);

        self::assertEquals($expectedSubs, $yt->listSubs($url));
    }

    public function testGetExtractorsList(): void
    {
        $process = new StaticProcess();
        $process->setOutputFile(__DIR__.'/Fixtures/extractors_list.txt');

        $processBuilder = $this->createProcessBuilderMock($process, ['--list-extractors']);

        $yt = new YoutubeDl($processBuilder);

        self::assertEquals([
            new Extractor(['title' => '1tv']),
            new Extractor(['title' => '9gag']),
            new Extractor(['title' => 'vimeo']),
            new Extractor(['title' => 'vimeo:album']),
            new Extractor(['title' => 'vimeo:channel']),
            new Extractor(['title' => 'youtube']),
            new Extractor(['title' => 'youtube:channel']),
            new Extractor(['title' => 'youtube:favorites']),
            new Extractor(['title' => 'youtube:history']),
            new Extractor(['title' => 'youtube:live']),
            new Extractor(['title' => 'youtube:playlist']),
            new Extractor(['title' => 'youtube:playlists']),
            new Extractor(['title' => 'youtube:recommended']),
            new Extractor(['title' => 'youtube:search']),
            new Extractor(['title' => 'youtube:subscriptions']),
            new Extractor(['title' => 'youtube:user']),
            new Extractor(['title' => 'youtube:watchlater']),
            new Extractor(['title' => 'Zype']),
        ], $yt->getExtractorsList());
    }

    public function testGetMultipleSystemOperatorsList(): void
    {
        $process = new StaticProcess();
        $process->setOutputFile(__DIR__.'/Fixtures/multiple_system_operators_list.txt');

        $processBuilder = $this->createProcessBuilderMock($process, ['--ap-list-mso']);

        $yt = new YoutubeDl($processBuilder);

        self::assertEquals([
            new Mso(['code' => 'ind060-ssc', 'name' => 'Silver Star Communications']),
            new Mso(['code' => 'jam030', 'name' => 'NVC']),
            new Mso(['code' => 'ada020', 'name' => 'Adams Cable Service']),
            new Mso(['code' => 'wcta', 'name' => 'Winnebago Cooperative Telecom Association']),
            new Mso(['code' => 'tri025', 'name' => 'TriCounty Telecom']),
            new Mso(['code' => 'hin020', 'name' => 'Hinton CATV Co.']),
            new Mso(['code' => 'fbcomm', 'name' => 'Frankfort Plant Board']),
            new Mso(['code' => 'wil015', 'name' => 'Wilson Communications']),
            new Mso(['code' => 'coo050', 'name' => 'Coon Valley Telecommunications Inc']),
            new Mso(['code' => 'gra060', 'name' => 'GLW Broadband Inc.']),
            new Mso(['code' => 'paulbunyan', 'name' => 'Paul Bunyan Communications']),
            new Mso(['code' => 'musfiber', 'name' => 'MUS FiberNET']),
            new Mso(['code' => 'alb020', 'name' => 'Albany Mutual Telephone']),
            new Mso(['code' => 'mtacomm', 'name' => 'MTA Communications, LLC']),
        ], $yt->getMultipleSystemOperatorsList());
    }

    public function provideSimpleVideoCases(): iterable
    {
        yield 'youtube_batman_trailer_2021' => [
            'url' => 'https://www.youtube.com/watch?v=-FZ-pPFAjYY',
            'outputFile' => __DIR__.'/Fixtures/youtube/batman_trailer_2021.txt',
            'metadataFile' => __DIR__.'/Fixtures/youtube/THE BATMAN Trailer (2021)--FZ-pPFAjYY.info.json',
        ];
    }

    public function provideDownloadPlaylistCases(): iterable
    {
        yield 'youtube two video playlist' => [
            'url' => 'https://www.youtube.com/playlist?list=PLiLPuNqqf8RT_0RsCdJ7uw0WHwvYiZ2hG',
            'outputFile' => __DIR__.'/Fixtures/youtube/two_video_playlist.txt',
            'metadataFiles' => [
                __DIR__.'/Fixtures/youtube/Pet Shop Boys - Did you see me coming-2Hy4bT0ESfc.info.json',
                __DIR__.'/Fixtures/youtube/Céline Dion - Ashes (from \'Deadpool 2\' Motion Picture Soundtrack)-CX11yw6YL1w.info.json',
            ],
        ];
    }

    public function provideDownloadPlaylistMatchTitleCases(): iterable
    {
        yield 'youtube video playlist match title' => [
            'url' => 'https://www.youtube.com/playlist?list=PLiLPuNqqf8RT_0RsCdJ7uw0WHwvYiZ2hG',
            'outputFile' => __DIR__.'/Fixtures/youtube/playlist_match_title_zero_results.txt',
            'expectedCollection' => new VideoCollection([]),
        ];
    }

    public function provideDownloadPlaylistRejectTitleCases(): iterable
    {
        yield 'youtube video playlist match title' => [
            'url' => 'https://www.youtube.com/playlist?list=PLiLPuNqqf8RT_0RsCdJ7uw0WHwvYiZ2hG',
            'outputFile' => __DIR__.'/Fixtures/youtube/playlist_reject_title_zero_results.txt',
            'expectedCollection' => new VideoCollection([]),
        ];
    }

    public function provideUnsupportedUrlCases(): iterable
    {
        yield 'youtube' => [
            'url' => 'https://youtube.com',
            'outputFile' => __DIR__.'/Fixtures/youtube/unsupported_url.txt',
            'expectedEntity' => new Video(['error' => 'Unsupported URL: https://www.youtube.com/', 'extractor' => 'generic']),
        ];
    }

    public function provideIncompleteUrlCases(): iterable
    {
        yield 'youtube' => [
            'url' => 'https://www.youtube.com/watch?v=X0lRjbrH-L',
            'outputFile' => __DIR__.'/Fixtures/youtube/incomplete_url.txt',
            'expectedEntity' => new Video(['error' => 'Incomplete YouTube ID X0lRjbrH-L. URL https://www.youtube.com/watch?v=X0lRjbrH-L looks truncated.', 'extractor' => 'generic']),
        ];
    }

    public function providePrivatePlaylistCases(): iterable
    {
        yield 'youtube' => [
            'url' => 'https://www.youtube.com/playlist?list=PLtPgu7CB4gbY9oDN3drwC3cMbJggS7dKl',
            'outputFile' => __DIR__.'/Fixtures/youtube/private_playlist.txt',
            'expectedEntity' => new Video(['error' => 'This playlist is private, use --username or --netrc to access it.', 'extractor' => 'youtube:playlist']),
        ];
    }

    public function provideUnreachableNetworkCases(): iterable
    {
        yield 'youtube' => [
            'url' => 'https://www.youtube.com/watch?v=-cRzcUxLxlM',
            'outputFile' => __DIR__.'/Fixtures/youtube/network_unreachable.txt',
            'expectedEntity' => new Video(['error' => 'unable to download video data: <urlopen error [Errno 101] Network is unreachable>', 'extractor' => 'youtube']),
        ];
    }

    public function provideListSubsCases(): iterable
    {
        yield 'youtube no subtitles' => [
            'url' => 'https://www.youtube.com/watch?v=t3Ww9Z0Kt78',
            'outputFile' => __DIR__.'/Fixtures/youtube/no_subtitles.txt',
            'expectedSubs' => [],
        ];

        yield 'youtube auto captions' => [
            'url' => 'https://www.youtube.com/watch?v=N26PICDnOAM',
            'outputFile' => __DIR__.'/Fixtures/youtube/auto_captions.txt',
            'expectedSubs' => [
                new SubListItem(['language' => 'gu', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'zh-Hans', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'zh-Hant', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'lt', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'fil', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'haw', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'ceb', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'hmn', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'sd', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
            ],
        ];

        yield 'youtube full subtitles' => [
            'url' => 'https://www.youtube.com/watch?v=X0lRjbrH-L8',
            'outputFile' => __DIR__.'/Fixtures/youtube/full_subtitles.txt',
            'expectedSubs' => [
                new SubListItem(['language' => 'gu', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'zh-Hans', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'zh-Hant', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'lt', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'fil', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'haw', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'ceb', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'hmn', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'sd', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => true]),
                new SubListItem(['language' => 'nl', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => false]),
                new SubListItem(['language' => 'hi-Latn', 'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], 'auto_caption' => false]),
            ],
        ];

        yield 'youtube playlist no subtitles' => [
            'url' => 'https://www.youtube.com/playlist?list=PLiLPuNqqf8RT_0RsCdJ7uw0WHwvYiZ2hG',
            'outputFile' => __DIR__.'/Fixtures/youtube/playlist_without_subtitles.txt',
            'expectedSubs' => [],
        ];
    }

    /**
     * @return ProcessBuilderInterface|MockObject
     */
    private function createProcessBuilderMock(Process $process, array $args)
    {
        /** @var ProcessBuilderInterface|MockObject $processBuilder */
        $processBuilder = $this->createMock(ProcessBuilderInterface::class);
        $processBuilder->expects(static::once())
            ->method('build')
            ->with(null, null, $args)
            ->willReturn($process);

        return $processBuilder;
    }

    private function readJsonFile(string $file): array
    {
        $content = file_get_contents($file);

        if ($content === false) {
            throw FileException::cannotRead($file);
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}
