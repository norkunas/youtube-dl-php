<?php

declare(strict_types=1);

namespace YoutubeDl\Tests\Entity;

use PHPUnit\Framework\TestCase;
use YoutubeDl\Entity\Mso;

final class MsoTest extends TestCase
{
    public function testGetters(): void
    {
        $mso = new Mso([
            'code' => 'ind060-ssc',
            'name' => 'Silver Star Communications',
        ]);

        self::assertSame('ind060-ssc', $mso->getCode());
        self::assertSame('Silver Star Communications', $mso->getName());
    }
}
