<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testCreateName(): void
    {
        /** @Given a valid name with the maximum allowed length */
        $expected = str_repeat('x', 255);

        /** @When I create a new Name instance */
        $actual = new Name(value: $expected);

        /** @Then the name value should match the expected value */
        self::assertEquals($expected, $actual->value);
    }

    public function testExceptionWhenEmptyName(): void
    {
        /** @Then an exception indicating that the name cannot be empty should be thrown */
        $this->expectException(EmptyName::class);
        $this->expectExceptionMessage('Name cannot be empty.');

        /** @When I try to create a Name with an empty value */
        new Name(value: '');
    }

    public function testExceptionWhenNameTooLong(): void
    {
        /** @Given a name value that exceeds the maximum allowed length */
        $value = str_repeat('x', 256);
        $template = 'Name is too long. Current <%d> characters, Maximum <%d> characters.';

        /** @Then an exception indicating that the name is too long should be thrown */
        $this->expectException(NameTooLong::class);
        $this->expectExceptionMessage(sprintf($template, strlen($value), 255));

        /** @When I try to create a Name with a value longer than allowed */
        new Name(value: $value);
    }
}
