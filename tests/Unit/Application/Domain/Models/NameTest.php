<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testExceptionWhenEmptyName(): void
    {
        $this->expectException(EmptyName::class);
        $this->expectExceptionMessage('Name cannot be empty.');

        new Name(value: '');
    }
}
