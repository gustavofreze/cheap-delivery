<?php

declare(strict_types=1);

namespace Test\Unit\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Decimal;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ValuesTest extends TestCase
{
    public static function nonPositiveProvider(): array
    {
        return [
            'Zero'     => ['value' => 0.00],
            'Negative' => ['value' => -1.00]
        ];
    }

    public function testRoundsToTwoDecimalPlaces(): void
    {
        /** @When a value with more precision than the scale is built */
        $cost = Cost::from(value: 10.005);

        /** @Then it is rounded half up to the scale */
        self::assertSame(10.01, $cost->toFloat());
    }

    public function testAddsCosts(): void
    {
        /** @Given two costs */
        $first = Cost::from(value: 10.00);
        $second = Cost::from(value: 86.40);

        /** @When they are added */
        $total = $first->plus(addend: $second);

        /** @Then the sum carries the scale */
        self::assertSame(96.40, $total->toFloat());
    }

    public function testComparesWeights(): void
    {
        /** @Given two weights */
        $light = Weight::from(value: 2.16);
        $threshold = Weight::from(value: 5.00);

        /** @Then they compare by value */
        self::assertTrue($light->isLessThan(other: $threshold));
        self::assertFalse($light->isGreaterThanOrEqual(other: $threshold));
        self::assertTrue($threshold->isGreaterThanOrEqual(other: $threshold));
    }

    public function testMultipliesDecimals(): void
    {
        /** @Given a distance and a weight */
        $distance = Distance::from(value: 800.00);
        $weight = Weight::from(value: 2.16);

        /** @When they are multiplied */
        $travelled = $distance->toDecimal()->multipliedBy(multiplier: $weight->toDecimal());

        /** @Then the product carries the scale */
        self::assertSame(1728.00, $travelled->toFloat());
        self::assertFalse($travelled->isZero());
        self::assertFalse($travelled->isNegative());
    }

    public function testBuildsADecimal(): void
    {
        /** @When a decimal is built from a float */
        $decimal = Decimal::of(value: 1.005);

        /** @Then it is rounded half up to the scale */
        self::assertSame(1.01, $decimal->toFloat());
    }

    #[DataProvider('nonPositiveProvider')]
    public function testRefusesANonPositiveCost(float $value): void
    {
        /** @Then the cost is refused */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage('Cost cannot be zero or negative.');

        /** @When a non positive cost is built */
        Cost::from(value: $value);
    }

    #[DataProvider('nonPositiveProvider')]
    public function testRefusesANonPositiveWeight(float $value): void
    {
        /** @Then the weight is refused */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage('Weight cannot be zero or negative.');

        /** @When a non positive weight is built */
        Weight::from(value: $value);
    }

    #[DataProvider('nonPositiveProvider')]
    public function testRefusesANonPositiveDistance(float $value): void
    {
        /** @Then the distance is refused */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage('Distance cannot be zero or negative.');

        /** @When a non positive distance is built */
        Distance::from(value: $value);
    }

    public function testRefusesAWeightAboveTheMaximum(): void
    {
        /** @Then the weight is refused */
        $this->expectException(WeightOutOfRange::class);
        $this->expectExceptionMessage('Weight is out of range. Current <2000.16>, Maximum <1000.00>.');

        /** @When a weight above the maximum is built */
        Weight::from(value: 2000.16);
    }

    public function testRefusesADistanceAboveTheMaximum(): void
    {
        /** @Then the distance is refused */
        $this->expectException(DistanceOutOfRange::class);
        $this->expectExceptionMessage('Distance is out of range. Current <20000.01>, Maximum <20000.00>.');

        /** @When a distance above the maximum is built */
        Distance::from(value: 20000.01);
    }

    public function testRefusesAnEmptyName(): void
    {
        /** @Then the name is refused */
        $this->expectException(EmptyName::class);
        $this->expectExceptionMessage('Name cannot be empty.');

        /** @When an empty name is built */
        Name::from(value: '');
    }

    public function testRefusesANameAboveTheMaximumLength(): void
    {
        /** @Then the name is refused */
        $this->expectException(NameTooLong::class);
        $this->expectExceptionMessage('Name is too long. Current <256> characters, Maximum <255> characters.');

        /** @When a name above the maximum length is built */
        Name::from(value: str_repeat('a', 256));
    }
}
