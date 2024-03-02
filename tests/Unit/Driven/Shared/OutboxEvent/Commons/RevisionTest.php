<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RevisionTest extends TestCase
{
    /**
     * @param int $value
     * @return void
     * @dataProvider invalidValueProvider
     */
    public function testExceptionWhenNonPositiveValue(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Revision cannot be zero or negative.');

        new Revision(value: $value);
    }

    public static function invalidValueProvider(): array
    {
        return [
            'zero'     => ['value' => 0],
            'negative' => ['value' => -1]
        ];
    }
}
