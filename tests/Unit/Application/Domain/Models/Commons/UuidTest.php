<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function testInvalidUuid(): void
    {
        /** @Given a value that is not a valid UUID */
        $value = 'not-a-valid-uuid';

        /** @Then an exception indicating invalid UUID should be thrown */
        $template = 'Invalid UUID <%s>.';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        /** @When I try to construct a UUID with this value */
        new Uuid(value: $value);
    }

    #[DataProvider('uuidProvider')]
    public function testIsUuidValid(string $value): void
    {
        /** @Given a valid UUID */
        /** @When I check if the value is a valid UUID */
        /** @Then it should return true */
        self::assertTrue(Uuid::isUuid(value: $value));
    }

    public static function uuidProvider(): iterable
    {
        yield 'UUID example 1' => ['550e8400-e29b-41d4-a716-446655440000'];

        yield 'UUID example 2' => ['00000000-1111-2222-3333-444444444444'];

        yield 'generated UUID v4' => [Uuid::generateV4()->toString()];
    }
}
