<?php

declare(strict_types=1);

namespace Test\Integration;

use CheapDelivery\Environment;
use TinyBlocks\DockerContainer\GenericDockerContainer;
use TinyBlocks\DockerContainer\MySQLDockerContainer;
use TinyBlocks\DockerContainer\Waits\Conditions\MySQL\MySQLReady;
use TinyBlocks\DockerContainer\Waits\ContainerWaitForDependency;
use TinyBlocks\DockerContainer\Waits\ContainerWaitForTime;

final readonly class Database
{
    private function __construct(
        private string $host,
        private string $database,
        private string $username,
        private string $password,
        private string $migrationFile
    ) {
    }

    public static function instance(): Database
    {
        $host = Environment::get(variable: 'DATABASE_HOST')->toString();
        $database = Environment::get(variable: 'DATABASE_NAME')->toString();
        $username = Environment::get(variable: 'DATABASE_USER')->toString();
        $password = Environment::get(variable: 'DATABASE_PASSWORD')->toString();
        $migrationFile = '/cheap-delivery-adm-migrations/.migrated';

        return new Database(
            host: $host,
            database: $database,
            username: $username,
            password: $password,
            migrationFile: $migrationFile
        );
    }

    public function start(): void
    {
        $mySQLContainer = MySQLDockerContainer::from(image: 'mysql:8.1', name: $this->host)
            ->withNetwork(name: 'cheap-delivery-test_default')
            ->withTimezone(timezone: 'America/Sao_Paulo')
            ->withPassword(password: $this->password)
            ->withDatabase(database: $this->database)
            ->withRootPassword(rootPassword: $this->password)
            ->withGrantedHosts()
            ->withVolumeMapping(pathOnHost: '/var/lib/mysql', pathOnContainer: '/var/lib/mysql')
            ->runIfNotExists();

        if (file_exists($this->migrationFile)) {
            return;
        }

        $jdbcUrl = $mySQLContainer->getJdbcUrl();

        GenericDockerContainer::from(image: 'flyway/flyway:11.0.1')
            ->withNetwork(name: 'cheap-delivery-test_default')
            ->copyToContainer(pathOnHost: '/cheap-delivery-adm-migrations', pathOnContainer: '/flyway/sql')
            ->withVolumeMapping(pathOnHost: '/cheap-delivery-adm-migrations', pathOnContainer: '/flyway/sql')
            ->withWaitBeforeRun(
                wait: ContainerWaitForDependency::untilReady(
                    condition: MySQLReady::from(
                        container: $mySQLContainer
                    )
                )
            )
            ->withEnvironmentVariable(key: 'FLYWAY_URL', value: $jdbcUrl)
            ->withEnvironmentVariable(key: 'FLYWAY_USER', value: $this->username)
            ->withEnvironmentVariable(key: 'FLYWAY_TABLE', value: 'schema_history')
            ->withEnvironmentVariable(key: 'FLYWAY_SCHEMAS', value: $this->database)
            ->withEnvironmentVariable(key: 'FLYWAY_EDITION', value: 'community')
            ->withEnvironmentVariable(key: 'FLYWAY_PASSWORD', value: $this->password)
            ->withEnvironmentVariable(key: 'FLYWAY_LOCATIONS', value: 'filesystem:/flyway/sql')
            ->withEnvironmentVariable(key: 'FLYWAY_CLEAN_DISABLED', value: 'false')
            ->withEnvironmentVariable(key: 'FLYWAY_VALIDATE_MIGRATION_NAMING', value: 'true')
            ->run(
                commands: ['-connectRetries=15', 'clean', 'migrate'],
                waitAfterStarted: ContainerWaitForTime::forSeconds(seconds: 5)
            );

        touch($this->migrationFile);
    }
}
