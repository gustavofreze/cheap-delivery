<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Distance;
use LogicException;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    public function testCreationOfDistanceWithValidValue(): void
    {
        $distance = new Distance(10.00);

        self::assertEquals(10.00, $distance->getValue());
    }

    /**
     * @param float $value
     * @return void
     * @dataProvider invalidDistance
     */
    public function testCreationOfDistanceWithInvalidValue(float $value): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Distance cannot be zero or negative');

        new Distance($value);
    }

    public function invalidDistance(): array
    {
        return [[0.0], [-1.0]];
    }
}
