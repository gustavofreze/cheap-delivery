<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Environment;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class EnvironmentTest extends TestCase
{
    public function testGetEnvironmentVariable(): void
    {
        $environment = new EnvironmentAdapter();
        $actual = $environment->get(variable: 'XPTO');

        self::assertEquals('xxxxxxxxxx', $actual);
    }

    public function testGetEnvironmentVariableIsMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Environment variable API_NAME is missing');

        $environment = new EnvironmentAdapter();
        $environment->get(variable: 'API_NAME');
    }
}
