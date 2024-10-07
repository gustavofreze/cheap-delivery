<?php

declare(strict_types=1);

namespace CheapDelivery;

use CheapDelivery\Application\Handlers\DispatchWithLowestCostHandler;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use CheapDelivery\Application\Ports\Outbound\Dispatches;
use CheapDelivery\Driven\Carrier\Repository\Adapter as CarriersRepositoryAdapter;
use CheapDelivery\Driven\Dispatch\Repository\Adapter as DispatchesAdapter;
use CheapDelivery\Driven\Shared\Database\MySql\MySqlEngine;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\Adapter as OutboxEventAdapter;
use CheapDelivery\Driven\Shared\OutboxEvent\OutboxEvent;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\DispatchWithLowestCost;
use CheapDelivery\Query\Dispatch\Database\Facade as DispatchQuery;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PDO;

use function DI\autowire;
use function DI\create;
use function DI\get;

final class Dependencies
{
    /**
     * @return array
     */
    public static function definitions(): array
    {
        return [
            Connection::class             => function () {
                return DriverManager::getConnection(
                    [
                        'driver'        => 'pdo_mysql',
                        'host'          => Environment::get(variable: 'MYSQL_DATABASE_HOST')->toString(),
                        'user'          => Environment::get(variable: 'MYSQL_DATABASE_USER')->toString(),
                        'port'          => Environment::get(variable: 'MYSQL_DATABASE_PORT')->toInt(),
                        'dbname'        => Environment::get(variable: 'MYSQL_DATABASE_NAME')->toString(),
                        'password'      => Environment::get(variable: 'MYSQL_DATABASE_PASSWORD')->toString(),
                        'driverOptions' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
                    ],
                    new Configuration()
                );
            },
            Dispatches::class             => autowire(DispatchesAdapter::class),
            OutboxEvent::class            => autowire(OutboxEventAdapter::class),
            DispatchQuery::class          => autowire(DispatchQuery::class),
            Carriers::class               => autowire(CarriersRepositoryAdapter::class),
            RelationalConnection::class   => autowire(MySqlEngine::class),
            DispatchWithLowestCost::class => create(DispatchWithLowestCost::class)->constructor(
                get(DispatchWithLowestCostHandler::class)
            ),
        ];
    }
}
