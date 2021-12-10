<?php

declare(strict_types=1);

namespace CheapDelivery;

use CheapDelivery\Core\Repository\Carriers;
use CheapDelivery\Driven\Carrier\CarrierRepository;
use CheapDelivery\Driven\Database\DatabaseSettings;
use CheapDelivery\Driven\Database\Mongo\MongoSettings;
use CheapDelivery\Driven\Database\NoSqlDatabase;
use CheapDelivery\Driven\Database\NoSqlDatabaseEngine;
use DI\ContainerBuilder;

use function DI\autowire;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    Carriers::class => autowire(CarrierRepository::class),
    NoSqlDatabase::class => autowire(NoSqlDatabaseEngine::class),
    DatabaseSettings::class => autowire(MongoSettings::class)
]);

/** @noinspection PhpUnusedLocalVariableInspection */
$container = $containerBuilder->build();
