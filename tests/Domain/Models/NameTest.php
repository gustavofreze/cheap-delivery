<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testCreationOfNameWithValidValue(): void
    {
        $name = new Name(value: 'Gustavo');

        self::assertEquals('Gustavo', $name->getValue());
    }

    public function testCreationOfNameWithAnInvalidValue(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new Name(value: '');
    }
}
