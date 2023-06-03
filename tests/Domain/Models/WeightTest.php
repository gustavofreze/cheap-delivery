<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCreationOfWeightWithValidValue(): void
    {
        $weight = new Weight(value: 100.99);

        self::assertEquals(100.99, $weight->getValue());
    }

    /**
     * @dataProvider invalidWeight
     */
    public function testCreationOfAnInvalidValueWeight(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Weight cannot be zero or negative');

        new Weight(value: $value);
    }

    public function testWhenOneWeightIsSmallerThanAnotherWeight(): void
    {
        $weight = new Weight(value: 0.99);
        $anotherWeight = new Weight(value: 1.00);

        $actual = $weight->smallerThan(weight: $anotherWeight);

        self::assertTrue($actual);
    }

    public function testWhenOneWeightIsGreaterThanOrEqualToAnotherWeight(): void
    {
        $weight = new Weight(value: 1000.01);
        $anotherWeight = new Weight(value: 1000.00);

        $actual = $weight->greaterThanOrEqual(weight: $anotherWeight);

        self::assertTrue($actual);
    }

    public function invalidWeight(): array
    {
        return [[0.0], [-1.0]];
    }
}
