<?php

declare(strict_types=1);

namespace Test\Unit\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Models\Commons\Decimal;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use PHPUnit\Framework\TestCase;

final class BoundariesTest extends TestCase
{
    public function testAcceptsTheMaximumWeight(): void
    {
        /** @When a weight exactly at the maximum is built */
        $weight = Weight::from(value: 1000.00);

        /** @Then it is accepted */
        self::assertSame(1000.00, $weight->toFloat());
    }

    public function testAcceptsTheMaximumDistance(): void
    {
        /** @When a distance exactly at the maximum is built */
        $distance = Distance::from(value: 20000.00);

        /** @Then it is accepted */
        self::assertSame(20000.00, $distance->toFloat());
    }

    public function testAcceptsANameAtTheMaximumLength(): void
    {
        /** @When a name exactly at the maximum length is built */
        $name = Name::from(value: str_repeat('a', 255));

        /** @Then it is accepted */
        self::assertSame(255, strlen($name->value));
    }

    public function testCountsCharactersAndNeverBytes(): void
    {
        /** @Given a name of 255 multibyte characters, which is more than 255 bytes */
        $value = str_repeat('á', 255);

        /** @When it is built */
        $name = Name::from(value: $value);

        /** @Then the length is counted in characters, so the name is accepted */
        self::assertSame(255, mb_strlen($name->value));
        self::assertSame(510, strlen($name->value));
    }

    public function testIsNotLessThanAnEqualValue(): void
    {
        /** @Given two equal decimals */
        $first = Decimal::of(value: 5.00);
        $second = Decimal::of(value: 5.00);

        /** @Then neither is less than the other */
        self::assertFalse($first->isLessThan(other: $second));
        self::assertTrue($first->isGreaterThanOrEqual(other: $second));
    }
}
