<?php

namespace CheapDelivery;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testExceptionWhenEnvironmentVariableIsMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Environment variable <API_NAME> is missing.');

        Environment::get(variable: 'API_NAME');
    }
}
