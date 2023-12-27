<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Process;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\ExecutableFinder;
use YoutubeDl\Exception\ExecutableNotFoundException;
use YoutubeDl\Process\DefaultProcessBuilder;

final class DefaultProcessBuilderTest extends TestCase
{
    public function testCustomBinPath(): void
    {
        $processBuilder = new DefaultProcessBuilder();

        $process = $processBuilder->build('/home/norkunas/youtube-dl', null);

        self::assertSame('\'/home/norkunas/youtube-dl\'', $process->getCommandLine());
    }

    public function testCustomBinAndPythonPath(): void
    {
        $processBuilder = new DefaultProcessBuilder();

        $process = $processBuilder->build('/home/norkunas/youtube-dl', '/usr/bin/python');

        self::assertSame('\'/usr/bin/python\' \'/home/norkunas/youtube-dl\'', $process->getCommandLine());
    }

    public function testReturnsYtDlp(): void
    {
        $executableFinder = $this->createMock(ExecutableFinder::class);
        $executableFinder->expects(self::once())
            ->method('find')
            ->with('yt-dlp')
            ->willReturn('/usr/bin/yt-dlp');

        $processBuilder = new DefaultProcessBuilder($executableFinder);

        self::assertSame("'/usr/bin/yt-dlp'", $processBuilder->build(null, null)->getCommandLine());
    }

    public function testReturnsYoutubeDl(): void
    {
        $executableFinder = $this->createMock(ExecutableFinder::class);
        $executableFinder->expects(self::exactly(2))
            ->method('find')
            ->withConsecutive(['yt-dlp'], ['youtube-dl'])
            ->willReturn(null, '/usr/bin/youtube-dl');

        $processBuilder = new DefaultProcessBuilder($executableFinder);

        self::assertSame("'/usr/bin/youtube-dl'", $processBuilder->build(null, null)->getCommandLine());
    }

    public function testThrowsWhenYtDlpAndYoutubeDlNotFound(): void
    {
        $executableFinder = $this->createMock(ExecutableFinder::class);
        $executableFinder->expects(self::exactly(2))
            ->method('find')
            ->withConsecutive(['yt-dlp'], ['youtube-dl'])
            ->willReturn(null, null);

        $processBuilder = new DefaultProcessBuilder($executableFinder);

        $this->expectException(ExecutableNotFoundException::class);
        $this->expectExceptionMessage('"yt-dlp" or "youtube-dl" executable was not found. Did you forgot to configure it\'s binary path? ex.: $yt->setBinPath(\'/usr/bin/yt-dlp\')');

        $processBuilder->build(null, null);
    }
}
