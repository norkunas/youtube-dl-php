<?php

declare(strict_types=1);

namespace YoutubeDl\Process;

use Symfony\Component\Process\Process;
use YoutubeDl\Exception\ExecutableNotFoundException;

interface ProcessBuilderInterface
{
    /**
     * @throws ExecutableNotFoundException if `youtube-dl` binary could not be located
     */
    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process;
}
