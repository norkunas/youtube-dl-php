<?php

declare(strict_types=1);

namespace YoutubeDl\Process;

use YoutubeDl\Options;

use function count;
use function is_array;
use function is_bool;

class ArgvBuilder
{
    /**
     * @return list<non-empty-string>
     */
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
                } elseif ($option === 'playlist-items' || $option === 'sub-lang' || $option === 'format-sort') {
                    if (count($value) > 0) {
                        $cmd[] = sprintf('--%s=%s', $option, implode(',', $value));
                    }
                } elseif ($option === 'add-header' || $option === 'extractor-args') {
                    foreach ($value as $key => $v) {
                        $cmd[] = sprintf('--%s=%s:%s', $option, $key, $v);
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
