<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testCreateName(): void
    {
        $expected = str_repeat('x', 255);
        $actual = new Name(value: $expected);

        self::assertEquals($expected, $actual->value);
    }

    public function testExceptionWhenEmptyName(): void
    {
        $this->expectException(EmptyName::class);
        $this->expectExceptionMessage('Name cannot be empty.');

        new Name(value: '');
    }

    public function testExceptionWhenNameTooLong(): void
    {
        $value = str_repeat('x', 256);
        $template = 'Name is too long. Current <%d> characters, Maximum <%d> characters.';
        $this->expectException(NameTooLong::class);
        $this->expectExceptionMessage(sprintf($template, strlen($value), 255));

        new Name(value: $value);
    }
}
