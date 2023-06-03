<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    public function testCreationOfDistanceWithValidValue(): void
    {
        $distance = new Distance(value: 10.00);

        self::assertEquals(10.00, $distance->getValue());
    }

    /**
     * @dataProvider invalidDistance
     */
    public function testCreationOfDistanceWithInvalidValue(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Distance cannot be zero or negative');

        new Distance(value: $value);
    }

    public function invalidDistance(): array
    {
        return [[0.0], [-1.0]];
    }
}
