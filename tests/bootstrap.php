<?php

declare(strict_types=1);

use DG\BypassFinals;
use TinyBlocks\DockerContainer\EnvironmentFlag;
use TinyBlocks\DockerContainer\FlywayDockerContainer;
use TinyBlocks\DockerContainer\MySQL\MySQLContainerStarted;
use TinyBlocks\DockerContainer\MySQLDockerContainer;

require_once __DIR__ . '/../vendor/autoload.php';

BypassFinals::enable();

$network = (string)(getenv('TEST_NETWORK') ?: 'cheap-delivery-test_default');

MySQLDockerContainer::from(name: 'cheap-delivery-adm-test', image: 'mysql:8.4')
    ->withNetwork(name: $network)
    ->withDatabase(database: 'cheap_delivery_adm_test')
    ->withRootPassword(rootPassword: 'root')
    ->runWhen(
        gate: EnvironmentFlag::enabled(name: 'RUN_MIGRATIONS'),
        then: static function (MySQLContainerStarted $mySQLStarted) use ($network): void {
            $template = '%s/../database/migrations';
            $migrations = sprintf($template, __DIR__);

            FlywayDockerContainer::from(name: 'cheap-delivery-flyway-test', image: 'flyway/flyway:13.7')
                ->withSource(password: 'root', username: 'root', container: $mySQLStarted)
                ->withNetwork(name: $network)
                ->withMigrations(pathOnHost: $migrations)
                ->withCleanDisabled(disabled: false)
                ->withConnectRetries(retries: 60)
                ->withValidateMigrationNaming(enabled: true)
                ->cleanAndMigrate();
        }
    );
