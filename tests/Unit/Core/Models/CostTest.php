<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Cost;
use LogicException;
use PHPUnit\Framework\TestCase;

final class CostTest extends TestCase
{
    public function testCreationOfCostWithValidValue(): void
    {
        $cost = new Cost(99.01);

        self::assertEquals(99.01, $cost->getValue());
    }

    /**
     * @param float $value
     * @return void
     * @dataProvider invalidCost
     */
    public function testCreationOfCostWithInvalidValue(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Cost cannot be zero or negative');

        new Cost($value);
    }

    public function testWhenOneCostIsGreaterThanAnotherCost(): void
    {
        $cost = new Cost(100.01);
        $anotherCost = new Cost(0.01);
        $anotherCost = $anotherCost->plus(new Cost(99.00));

        self::assertTrue($cost->isGreaterThan($anotherCost));
    }

    public function invalidCost(): array
    {
        return [[0.0], [-1.0]];
    }
}
