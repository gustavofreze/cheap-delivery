<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Name;
use LogicException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testCreationOfNameWithValidValue(): void
    {
        $name = new Name('Gustavo');

        self::assertEquals('Gustavo', $name->getValue());
    }

    public function testCreationOfNameWithAnInvalidValue(): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Name cannot be empty');

        new Name('');
    }
}
