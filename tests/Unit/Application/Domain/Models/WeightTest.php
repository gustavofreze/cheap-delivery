<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCreateWeight(): void
    {
        $expected = 1000.00;
        $actual = new Weight(value: $expected);

        self::assertInstanceOf(PositiveDecimal::class, $actual);
        self::assertEquals($expected, $actual->value);
    }

    public function testExceptionWhenWeightOutOfRange(): void
    {
        $template = 'Weight is out of range. Current <%.2f>, Maximum <%.2f>.';
        $this->expectException(WeightOutOfRange::class);
        $this->expectExceptionMessage(sprintf($template, 1000.01, 1000.00));

        new Weight(value: 1000.01);
    }

    /**
     * @param float $value
     * @return void
     * @dataProvider invalidValueProvider
     */
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        $template = 'Weight cannot be zero or negative. Invalid value <%s>.';
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        new Weight(value: $value);
    }

    public static function invalidValueProvider(): array
    {
        return [
            'zero'     => ['value' => 0.0],
            'negative' => ['value' => -1.0]
        ];
    }
}
