<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\Extractor;

final class ExtractorTest extends TestCase
{
    public function testGetter(): void
    {
        $extractor = new Extractor([
            'title' => 'youtube:channel',
        ]);

        self::assertSame('youtube:channel', $extractor->getTitle());
    }
}
