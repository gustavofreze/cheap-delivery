<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PositiveDecimalTest extends TestCase
{
    #[DataProvider('lessThanProvider')]
    public function testIsLessThan(float $value, float $other, bool $expected): void
    {
        /** @Given a decimal value and another decimal to compare */
        $decimalValue = new PositiveDecimal(value: $value);
        $otherDecimalValue = new PositiveDecimal(value: $other);

        /** @When I check if the first value is less than the second */
        $actual = $decimalValue->isLessThan(other: $otherDecimalValue);

        /** @Then the result should match the expected outcome */
        self::assertEquals($expected, $actual);
    }

    #[DataProvider('greaterThanOrEqualProvider')]
    public function testIsGreaterThanOrEqual(float $value, float $other, bool $expected): void
    {
        /** @Given a decimal value and another decimal to compare */
        $decimalValue = new PositiveDecimal(value: $value);
        $otherDecimalValue = new PositiveDecimal(value: $other);

        /** @When I check if the first value is greater than or equal to the second */
        $actual = $decimalValue->isGreaterThanOrEqual(other: $otherDecimalValue);

        /** @Then the result should match the expected outcome */
        self::assertEquals($expected, $actual);
    }

    public static function lessThanProvider(): iterable
    {
        yield 'value equal to other' => [5.5, 5.5, false];

        yield 'value less than other' => [5.5, 4.5, false];

        yield 'value greater than other' => [99.98, 99.99, true];
    }

    public static function greaterThanOrEqualProvider(): iterable
    {
        yield 'value equal to other' => [5.5, 5.5, true];

        yield 'value less than other' => [99.98, 99.99, false];

        yield 'value greater than other' => [5.5, 4.5, true];
    }
}
