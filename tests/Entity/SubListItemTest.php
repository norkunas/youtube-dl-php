<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\SubListItem;

final class SubListItemTest extends TestCase
{
    public function testGetter(): void
    {
        $subListItem = new SubListItem([
            'language' => 'zh-Hans',
            'formats' => ['vtt', 'ttml', 'srv3', 'srv2', 'srv1'],
            'auto_caption' => true,
        ]);

        self::assertSame('zh-Hans', $subListItem->getLanguage());
        self::assertSame(['vtt', 'ttml', 'srv3', 'srv2', 'srv1'], $subListItem->getFormats());
        self::assertTrue($subListItem->isAutoCaption());
    }
}
