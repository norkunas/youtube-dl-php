<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\Category;
use YoutubeDl\Entity\Comment;
use YoutubeDl\Entity\Format;
use YoutubeDl\Entity\Subtitles;
use YoutubeDl\Entity\Thumbnail;
use YoutubeDl\Entity\Video;

final class VideoTest extends TestCase
{
    public function testConvertsDataToSubEntities(): void
    {
        $video = new Video([
            'categories' => ['Entertainment', 'Music'],
            'comments' => [
                [
                    'author' => 'John Doe',
                    'author_id' => 'john-doe',
                    'id' => '8fda5aab-ea4c-4899-91c4-84f799c4fb31',
                    'text' => 'comment',
                    'html' => '<p>comment</p>',
                    'timestamp' => 1601978912,
                    'parent' => null,
                ],
            ],
            'formats' => [
                [
                    'format' => 'mp4',
                ],
            ],
            'requested_formats' => [
                [
                    'format' => 'mp4',
                ],
            ],
            'thumbnails' => [
                [
                    'id' => '0',
                    'url' => 'https://example.com/thumb1.png',
                    'width' => 100,
                    'height' => 100,
                ],
            ],
            'subtitles' => [
                [
                    'url' => 'https://example.com/subtitles.vtt',
                    'ext' => 'vtt',
                ],
            ],
            'requested_subtitles' => [
                [
                    'url' => 'https://example.com/subtitles.vtt',
                    'ext' => 'vtt',
                ],
            ],
            'automatic_captions' => [
                [
                    'url' => 'https://example.com/subtitles.vtt',
                    'ext' => 'vtt',
                ],
            ],
        ]);

        $category1 = new Category(['title' => 'Entertainment']);
        $category2 = new Category(['title' => 'Music']);
        $comment = new Comment([
            'author' => 'John Doe',
            'author_id' => 'john-doe',
            'id' => '8fda5aab-ea4c-4899-91c4-84f799c4fb31',
            'text' => 'comment',
            'html' => '<p>comment</p>',
            'timestamp' => 1601978912,
            'parent' => null,
        ]);
        $format = new Format(['format' => 'mp4']);
        $thumbnail = new Thumbnail([
            'id' => '0',
            'url' => 'https://example.com/thumb1.png',
            'width' => 100,
            'height' => 100,
        ]);
        $subtitles = new Subtitles([
            'url' => 'https://example.com/subtitles.vtt',
            'ext' => 'vtt',
        ]);

        self::assertEquals([$category1, $category2], $video->getCategories());
        self::assertEquals([$comment], $video->getComments());
        self::assertEquals([$format], $video->getFormats());
        self::assertEquals([$format], $video->getRequestedFormats());
        self::assertEquals([$thumbnail], $video->getThumbnails());
        self::assertEquals([$subtitles], $video->getSubtitles());
        self::assertEquals([$subtitles], $video->getRequestedSubtitles());
        self::assertEquals([$subtitles], $video->getAutomaticCaptions());
    }
}
