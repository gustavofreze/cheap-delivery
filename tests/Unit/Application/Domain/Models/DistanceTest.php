<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    /**
     * @param float $value
     * @return void
     * @dataProvider invalidValueProvider
     */
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        $template = 'Distance cannot be zero or negative. Invalid value <%s>.';
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        new Distance(value: $value);
    }

    public static function invalidValueProvider(): array
    {
        return [
            'zero'     => ['value' => 0.0],
            'negative' => ['value' => -1.0]
        ];
    }
}
