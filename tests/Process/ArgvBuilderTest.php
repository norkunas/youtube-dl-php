<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Process;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use YoutubeDl\Options;
use YoutubeDl\Process\ArgvBuilder;

final class ArgvBuilderTest extends TestCase
{
    public function testFluentBuild(): void
    {
        $options = Options::create()
            ->downloadPath('/path/to/downloads')
            ->proxy('127.0.0.5')
            ->socketTimeout(5)
            ->headers([
                'Accept' => 'text/html',
            ])
            ->header('User-Agent', 'yt-dlp')
            ->yesPlaylist()
            ->playlistItems(['1-3', '7', '10-13'])
            ->dateBefore(new DateTimeImmutable('2020-08-31'))
            ->dateAfter(new DateTimeImmutable('2020-08-01'))
            ->extractorsArgs([
                'youtube' => 'player-client=mediaconnect,web;formats=incomplete',
            ])
            ->extractorArgs('funimation', 'version=uncut')
            ->url(
                'https://www.youtube.com/watch?v=-FZ-pPFAjYY',
                'https://www.youtube.com/watch?v=Q-g_YNZ90tI',
            );

        self::assertSame([
            '--proxy=127.0.0.5',
            '--socket-timeout=5',
            '--playlist-items=1-3,7,10-13',
            '--datebefore=20200831',
            '--dateafter=20200801',
            '--yes-playlist',
            '--output=/path/to/downloads/%(title)s-%(id)s.%(ext)s',
            '--add-header=Accept:text/html',
            '--add-header=User-Agent:yt-dlp',
            '--extractor-args=youtube:player-client=mediaconnect,web;formats=incomplete',
            '--extractor-args=funimation:version=uncut',
            'https://www.youtube.com/watch?v=-FZ-pPFAjYY',
            'https://www.youtube.com/watch?v=Q-g_YNZ90tI',
        ], ArgvBuilder::build($options));
    }
}
