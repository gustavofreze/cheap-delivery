<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    #[DataProvider('validWeightValuesProvider')]
    public function testCreateWeightWithValidValues(float $value, float $expected): void
    {
        /** @Given a valid weight value */
        /** @When I create a new Weight instance */
        $actual = new Weight(value: $value);

        /** @Then the instance should be a PositiveDecimal and the value should match the expected */
        self::assertEquals($expected, $actual->value);
    }

    public function testExceptionWhenWeightOutOfRange(): void
    {
        /** @Given a weight value that exceeds the maximum allowed */
        $template = 'Weight is out of range. Current <%.2f>, Maximum <%.2f>.';

        /** @Then an exception indicating that the weight is out of range should be thrown */
        $this->expectException(WeightOutOfRange::class);
        $this->expectExceptionMessage(sprintf($template, 1000.01, 1000.00));

        /** @When I try to create a Weight instance with a value out of range */
        new Weight(value: 1000.01);
    }

    #[DataProvider('invalidValueProvider')]
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        /** @Given a non-positive weight value */
        $template = 'Weight cannot be zero or negative. Invalid value <%s>.';

        /** @Then an exception for non-positive weight should be thrown */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        /** @When I try to create a Weight instance with this value */
        new Weight(value: $value);
    }

    public static function validWeightValuesProvider(): array
    {
        return [
            'Weight below 1000.0'     => ['value' => 999.99, 'expected' => 999.99],
            'Weight exactly 1000.0'   => ['value' => 1000.0, 'expected' => 1000.0],
            'Minimum positive weight' => ['value' => 0.01, 'expected' => 0.01]
        ];
    }

    public static function invalidValueProvider(): iterable
    {
        yield 'zero value' => ['value' => 0.0];
        yield 'negative value' => ['value' => -1.0];
    }
}
