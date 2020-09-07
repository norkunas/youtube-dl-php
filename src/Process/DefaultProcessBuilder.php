<?php

declare(strict_types=1);

namespace YoutubeDl\Process;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use YoutubeDl\Exception\ExecutableNotFoundException;

final class DefaultProcessBuilder implements ProcessBuilderInterface
{
    private ExecutableFinder $executableFinder;

    public function __construct(?ExecutableFinder $executableFinder = null)
    {
        $this->executableFinder = $executableFinder ?? new ExecutableFinder();
    }

    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process
    {
        $binPath = $binPath ?: $this->executableFinder->find('youtube-dl');

        if ($binPath === null) {
            throw new ExecutableNotFoundException('"youtube-dl" executable was not found. Did you forgot to configure it\'s binary path? ex.: $yt->setBinPath(\'/usr/bin/youtube-dl\') ?.');
        }

        array_unshift($arguments, $binPath);

        if ($pythonPath !== null) {
            array_unshift($arguments, $pythonPath);
        }

        $process = new Process($arguments);
        $process->setTimeout(null);

        return $process;
    }
}
