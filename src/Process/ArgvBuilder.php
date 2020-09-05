<?php

declare(strict_types=1);

namespace YoutubeDl\Process;

use YoutubeDl\Options;
use function count;
use function is_array;
use function is_bool;

class ArgvBuilder
{
    public static function build(Options $options): array
    {
        $cmd = [];

        foreach ($options->toArray() as $option => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $cmd[] = "--$option";
                }
            } elseif (is_array($value)) {
                if ($option === 'url') {
                    foreach ($value as $url) {
                        $cmd[] = $url;
                    }
                } elseif ($option === 'playlist-items' || $option === 'sub-lang') {
                    if (count($value) > 0) {
                        $cmd[] = sprintf('--%s=%s', $option, implode(',', $value));
                    }
                } elseif ($option === 'add-header') {
                    foreach ($value as $header => $headerValue) {
                        $cmd[] = sprintf('--%s=%s:%s', $option, $header, $headerValue);
                    }
                } else {
                    foreach ($value as $v) {
                        $cmd[] = "--$option=$v";
                    }
                }
            } elseif ($value !== null) {
                $cmd[] = "--$option=$value";
            }
        }

        return $cmd;
    }
}
