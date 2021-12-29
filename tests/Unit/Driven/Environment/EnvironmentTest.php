<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Driven\Environment;

use CheapDelivery\Driven\Environment\EnvironmentAdapter;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class EnvironmentTest extends TestCase
{
    public function testGetEnvironmentVariable(): void
    {
        $environment = new EnvironmentAdapter();
        $actual = $environment->get('XPTO');

        self::assertEquals('xxxxxxxxxx', $actual);
    }

    public function testGetEnvironmentVariableIsMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Environment variable API_NAME is missing');

        $environment = new EnvironmentAdapter();
        $environment->get('API_NAME');
    }
}
