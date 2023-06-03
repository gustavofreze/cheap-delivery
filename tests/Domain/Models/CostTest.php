<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

final class CostTest extends TestCase
{
    public function testCreationOfCostWithValidValue(): void
    {
        $cost = new Cost(value: 99.01);

        self::assertEquals(99.01, $cost->getValue());
    }

    /**
     * @dataProvider invalidCost
     */
    public function testCreationOfCostWithInvalidValue(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cost cannot be zero or negative');

        new Cost(value: $value);
    }

    public function testWhenOneCostIsGreaterThanAnotherCost(): void
    {
        $cost = new Cost(value: 100.01);
        $anotherCost = new Cost(value: 0.01);
        $anotherCost = $anotherCost->plus(new Cost(value: 99.00));

        $actual = $cost->isGreaterThan($anotherCost);

        self::assertTrue($actual);
    }

    public function invalidCost(): array
    {
        return [[0.0], [-1.0]];
    }
}
