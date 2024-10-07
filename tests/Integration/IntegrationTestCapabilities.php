<?php

declare(strict_types=1);

namespace Test\Integration;

use CheapDelivery\Dependencies;
use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class IntegrationTestCapabilities extends TestCase
{
    protected static ContainerInterface $container;

    public static function setUpBeforeClass(): void
    {
        self::$container = new Container(Dependencies::definitions());
    }
}
