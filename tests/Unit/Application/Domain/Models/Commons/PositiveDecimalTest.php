<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use PHPUnit\Framework\TestCase;

class PositiveDecimalTest extends TestCase
{
    /**
     * @param float $value
     * @param float $other
     * @param bool $expected
     * @return void
     * @dataProvider lessThanProvider
     */
    public function testIsLessThan(float $value, float $other, bool $expected): void
    {
        $decimalValue = new PositiveDecimal(value: $value);
        $otherDecimalValue = new PositiveDecimal(value: $other);

        self::assertEquals($expected, $decimalValue->isLessThan($otherDecimalValue));
    }

    /**
     * @param float $value
     * @param float $other
     * @param bool $expected
     * @return void
     * @dataProvider greaterThanOrEqualProvider
     */
    public function testIsGreaterThanOrEqual(float $value, float $other, bool $expected): void
    {
        $decimalValue = new PositiveDecimal(value: $value);
        $otherDecimalValue = new PositiveDecimal(value: $other);

        self::assertEquals($expected, $decimalValue->isGreaterThanOrEqual($otherDecimalValue));
    }

    public static function lessThanProvider(): array
    {
        return [
            'value_less'    => ['value' => 5.5, 'other' => 4.5, 'expected' => false],
            'value_equal'   => ['value' => 5.5, 'other' => 5.5, 'expected' => false],
            'value_greater' => ['value' => 99.98, 'other' => 99.99, 'expected' => true]
        ];
    }

    public static function greaterThanOrEqualProvider(): array
    {
        return [
            'value_less'    => ['value' => 5.5, 'other' => 4.5, 'expected' => true],
            'value_equal'   => ['value' => 5.5, 'other' => 5.5, 'expected' => true],
            'value_greater' => ['value' => 99.98, 'other' => 99.99, 'expected' => false]
        ];
    }
}
