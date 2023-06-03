<?php

declare(strict_types=1);

namespace CheapDelivery;

use CheapDelivery\Domain\Ports\Outbound\Carriers;
use CheapDelivery\Driven\Carrier\CarrierRepository;
use CheapDelivery\Driven\Database\DatabaseSettings;
use CheapDelivery\Driven\Database\Mongo\MongoSettings;
use CheapDelivery\Driven\Database\NoSqlDatabase;
use CheapDelivery\Driven\Database\NoSqlDatabaseEngine;
use CheapDelivery\Driven\Environment\Environment;
use CheapDelivery\Driven\Environment\EnvironmentAdapter;

use function DI\autowire;

return [
    Carriers::class => autowire(CarrierRepository::class),
    Environment::class => autowire(EnvironmentAdapter::class),
    NoSqlDatabase::class => autowire(NoSqlDatabaseEngine::class),
    DatabaseSettings::class => autowire(MongoSettings::class)
];
