<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;

final class VideoCollectionTest extends TestCase
{
    public function testToArray(): void
    {
        $thumbnail = [
            'id' => '0',
            'url' => 'https://example.com/thumb1.png',
            'width' => 100,
            'height' => 100,
        ];

        $v1 = new Video(['thumbnails' => [$thumbnail]]);
        $v2 = new Video(['categories' => ['Entertainment', 'Music']]);

        $collection = new VideoCollection([$v1, $v2]);

        self::assertSame([
            [
                'thumbnails' => [$thumbnail],
            ],
            [
                'categories' => [
                    ['title' => 'Entertainment'],
                    ['title' => 'Music'],
                ],
            ],
        ], $collection->toArray());
    }
}
