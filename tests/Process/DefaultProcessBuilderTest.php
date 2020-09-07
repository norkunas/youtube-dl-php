<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Process;

use PHPUnit\Framework\MockObject\MockObject;
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

    public function testThrowsWhenYoutubeDlNotFound(): void
    {
        $this->expectException(ExecutableNotFoundException::class);
        $this->expectExceptionMessage('"youtube-dl" executable was not found. Did you forgot to configure it\'s binary path? ex.: $yt->setBinPath(\'/usr/bin/youtube-dl\')');

        /** @var ExecutableFinder|MockObject $executableFinder */
        $executableFinder = $this->createMock(ExecutableFinder::class);
        $executableFinder->expects(static::once())
            ->method('find')
            ->with('youtube-dl')
            ->willReturn(null);

        $processBuilder = new DefaultProcessBuilder($executableFinder);

        $processBuilder->build(null, null);
    }
}
