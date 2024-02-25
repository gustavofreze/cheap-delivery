<?php

namespace CheapDelivery;

use CheapDelivery\Application\Ports\Outbound\CarriersRepository;
use CheapDelivery\Application\Ports\Outbound\ShipmentsRepository;
use CheapDelivery\Driven\Carrier\Repository\Adapter as CarriersRepositoryAdapter;
use CheapDelivery\Driven\Shared\Database\MySql\MySqlEngine;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\Adapter as OutboxEventAdapter;
use CheapDelivery\Driven\Shared\OutboxEvent\OutboxEvent;
use CheapDelivery\Driven\Shipment\Repository\Adapter as ShipmentsRepositoryAdapter;
use CheapDelivery\Query\Shipment\Database\Facade as ShipmentFacade;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PDO;

use function DI\autowire;

final class Dependencies
{
    /**
     * @return mixed[]
     */
    public static function definitions(): array
    {
        return [
            Connection::class           => function () {
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
            OutboxEvent::class          => autowire(OutboxEventAdapter::class),
            ShipmentFacade::class       => autowire(ShipmentFacade::class),
            CarriersRepository::class   => autowire(CarriersRepositoryAdapter::class),
            ShipmentsRepository::class  => autowire(ShipmentsRepositoryAdapter::class),
            RelationalConnection::class => autowire(MySqlEngine::class)
        ];
    }
}
