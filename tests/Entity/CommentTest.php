<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\Comment;

final class CommentTest extends TestCase
{
    public function testGetters(): void
    {
        $comment = new Comment([
            'author_id' => 'doeJohn',
            'author' => 'John Doe',
            'text' => 'Best',
            'html' => '<strong>Best</strong>',
            'timestamp' => 1599124094,
            'id' => '01EHBWRHAA58RNX7B9BKTCFM03',
            'parent' => '01EHBWQDW6XJ5S2JQ97KP8RQVY',
        ]);

        self::assertSame('John Doe', $comment->getAuthor());
        self::assertSame('doeJohn', $comment->getAuthorId());
        self::assertSame('01EHBWRHAA58RNX7B9BKTCFM03', $comment->getId());
        self::assertSame('<strong>Best</strong>', $comment->getHtml());
        self::assertSame('Best', $comment->getText());
        self::assertSame(1599124094, $comment->getTimestamp());
        self::assertSame('01EHBWQDW6XJ5S2JQ97KP8RQVY', $comment->getParent());
    }
}
