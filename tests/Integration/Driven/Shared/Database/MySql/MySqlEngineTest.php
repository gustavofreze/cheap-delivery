<?php

declare(strict_types=1);

namespace Test\Integration\Driven\Shared\Database\MySql;

use CheapDelivery\Driven\Shared\Database\MySql\MySqlEngine;
use CheapDelivery\Driven\Shared\Database\MySql\MySqlFailure;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use Doctrine\DBAL\Connection;
use Exception;
use Test\Integration\IntegrationTestCapabilities;

class MySqlEngineTest extends IntegrationTestCapabilities
{
    private const string INSERT = 'INSERT INTO test_table (name) VALUES (:name);';
    private const string SELECT_ALL = 'SELECT * FROM test_table;';
    private const string DROP_TABLE = 'DROP TEMPORARY TABLE test_table;';
    private const string CREATE_TABLE = '
            CREATE TEMPORARY TABLE test_table
            (
                id   INT AUTO_INCREMENT,
                name VARCHAR(255),
                PRIMARY KEY (id)
            );';

    private Connection $connection;

    private MySqlEngine $mySqlEngine;

    protected function setUp(): void
    {
        $this->connection = self::$container->get(Connection::class);
        $this->connection->executeStatement(sql: self::CREATE_TABLE);

        $this->mySqlEngine = new MySqlEngine(connection: $this->connection);
    }

    protected function tearDown(): void
    {
        $this->connection->executeStatement(sql: self::DROP_TABLE);
    }

    public function testInTransactionRollbackOnException(): void
    {
        /** @Given that I have operations to be executed in a transaction */
        $useCase = function (RelationalConnection $connection) {
            $connection->with()
                ->query(sql: self::INSERT)
                ->bind(data: ['name' => 'Record 01'])
                ->execute();

            $connection->with()
                ->query(sql: self::INSERT)
                ->bind(data: ['name' => 'Record 02'])
                ->execute();

            /** @And an error occurs */
            throw new Exception(message: 'Test exception');
        };

        /** @Then an exception indicating failure in a MySQL operation should occur */
        $this->expectException(MySqlFailure::class);
        $this->expectExceptionMessage(
            'MySQL operation <Execute a set of operations within a transaction> failed. Reason: <Test exception>'
        );

        /** @When I execute the operations */
        $this->mySqlEngine->inTransaction(useCase: $useCase);

        /** @And no data should be persisted */
        $actual = $this->connection
            ->prepare(sql: self::SELECT_ALL)
            ->executeQuery()
            ->fetchAllAssociative();

        self::assertEmpty($actual);
    }
}
