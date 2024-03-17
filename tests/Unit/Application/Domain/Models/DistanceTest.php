<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    public function testCreateDistance(): void
    {
        $expected = 20000.00;
        $actual = new Distance(value: $expected);

        self::assertInstanceOf(PositiveDecimal::class, $actual);
        self::assertEquals($expected, $actual->value);
    }

    public function testExceptionWhenDistanceOutOfRange(): void
    {
        $template = 'Distance is out of range. Current <%.2f>, Maximum <%.2f>.';
        $this->expectException(DistanceOutOfRange::class);
        $this->expectExceptionMessage(sprintf($template, 20000.01, 20000.00));

        new Distance(value: 20000.01);
    }

    /**
     * @param float $value
     * @return void
     * @dataProvider invalidValueProvider
     */
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        $template = 'Distance cannot be zero or negative. Invalid value <%s>.';
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        new Distance(value: $value);
    }

    public static function invalidValueProvider(): array
    {
        return [
            'zero'     => ['value' => 0.0],
            'negative' => ['value' => -1.0]
        ];
    }
}
