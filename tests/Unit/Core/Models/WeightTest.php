<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Weight;
use LogicException;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCreationOfWeightWithValidValue(): void
    {
        $weight = new Weight(100.99);

        self::assertEquals(100.99, $weight->getValue());
    }

    /**
     * @param float $value
     * @return void
     * @dataProvider invalidWeight
     */
    public function testCreationOfAnInvalidValueWeight(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Weight cannot be zero or negative');

        new Weight($value);
    }

    public function testWhenOneWeightIsSmallerThanAnotherWeight(): void
    {
        $weight = new Weight(0.99);
        $anotherWeight = new Weight(1.00);

        self::assertTrue($weight->smallerThan($anotherWeight));
    }

    public function testWhenOneWeightIsGreaterThanOrEqualToAnotherWeight(): void
    {
        $weight = new Weight(1000.01);
        $anotherWeight = new Weight(1000.00);

        self::assertTrue($weight->greaterThanOrEqual($anotherWeight));
    }

    public function invalidWeight(): array
    {
        return [[0.0], [-1.0]];
    }
}
