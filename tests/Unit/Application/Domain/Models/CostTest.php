<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use PHPUnit\Framework\TestCase;

class CostTest extends TestCase
{
    /**
     * @param float $value
     * @return void
     * @dataProvider invalidValueProvider
     */
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        $template = 'Cost cannot be zero or negative. Invalid value <%s>.';
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        new Cost(value: $value);
    }

    public static function invalidValueProvider(): array
    {
        return [[0.0], [-1.0]];
    }
}
