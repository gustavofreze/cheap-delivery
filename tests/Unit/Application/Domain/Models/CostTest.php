<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CostTest extends TestCase
{
    #[DataProvider('invalidValueProvider')]
    public function testExceptionWhenNonPositiveValue(float $value): void
    {
        /** @Given a non-positive cost value */
        $template = 'Cost cannot be zero or negative. Invalid value <%s>.';

        /** @Then an exception indicating the value is non-positive should be thrown */
        $this->expectException(NonPositiveValue::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        /** @When I try to construct a Cost with this value */
        new Cost(value: $value);
    }

    public static function invalidValueProvider(): iterable
    {
        yield 'zero value' => [0.0];
        yield 'negative value' => [-1.0];
    }
}
