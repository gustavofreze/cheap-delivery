<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RevisionTest extends TestCase
{
    #[DataProvider('invalidValueProvider')]
    public function testExceptionWhenNonPositiveValue(int $value): void
    {
        /** @Then an exception indicating that the revision cannot be zero or negative should be thrown */
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Revision cannot be zero or negative.');

        /** @When I try to create a Revision instance with this value */
        new Revision(value: $value);
    }

    public static function invalidValueProvider(): iterable
    {
        yield 'zero value' => [0];
        yield 'negative value' => [-1];
    }
}
