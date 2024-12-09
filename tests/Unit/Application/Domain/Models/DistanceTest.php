<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    public function testCreateDistance(): void
    {
        /** @Given a valid distance value */
        $expected = 20000.00;

        /** @When I create a new Distance instance */
        $actual = new Distance(value: $expected);

        /** @Then the instance should be a PositiveDecimal and the value should match the expected */
        self::assertEquals($expected, $actual->value);
    }

    #[DataProvider('outOfRangeValuesDataProvider')]
    public function testExceptionWhenDistanceOutOfRange(float $value): void
    {
        /** @Given a distance value that exceeds the maximum allowed */
        $template = 'Distance is out of range. Current <%.2f>, Maximum <%.2f>.';

        /** @Then an exception for distance out of range should be thrown */
        $this->expectException(DistanceOutOfRange::class);
        $this->expectExceptionMessage(sprintf($template, $value, 20000.00));

        /** @When I try to create a Distance with a value out of range */
        new Distance(value: $value);
    }

    #[DataProvider('invalidValueDataProvider')]
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        /** @Given a non-positive distance value */
        $template = 'Distance cannot be zero or negative. Invalid value <%s>.';

        /** @Then an exception for a non-positive value should be thrown */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        /** @When I try to create a Distance with this value */
        new Distance(value: $value);
    }

    public static function invalidValueDataProvider(): iterable
    {
        yield 'Zero value' => [0.0];
        yield 'Negative value' => [-1.0];
    }

    public static function outOfRangeValuesDataProvider(): iterable
    {
        yield 'Slightly above maximum limit' => [20000.01];
        yield 'Significantly above maximum limit' => [30000.00];
    }
}
