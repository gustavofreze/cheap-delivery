<?php

namespace Test\Unit\Driven\Shared\Database\MySql;

use CheapDelivery\Driven\Shared\Database\MySql\MySqlFailure;
use CheapDelivery\Driven\Shared\Database\MySql\MySqlQueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class MySqlQueryBuilderTest extends TestCase
{
    private Result|MockObject $result;

    private Statement|MockObject $statement;

    private Connection|MockObject $connection;

    private MySqlQueryBuilder $queryBuilder;

    protected function setUp(): void
    {
        $this->result = $this->createMock(Result::class);
        $this->statement = $this->createMock(Statement::class);
        $this->connection = $this->createMock(Connection::class);

        $this->statement->method('executeQuery')->willReturn($this->result);
        $this->connection->method('prepare')->willReturn($this->statement);

        $this->queryBuilder = new MySqlQueryBuilder(connection: $this->connection);
    }

    public function testExceptionWhenMapFailure(): void
    {
        /** @Given that I have the result of a query */
        $result = $this->result->method('fetchAllAssociative');

        /** @And an error occurs when executing the fetchAllAssociative method */
        $result->willThrowException(new RuntimeException(message: 'Test exception'));

        /** @Then an exception indicating mapping failure should occur */
        $this->expectException(MySqlFailure::class);

        /** @When the map method is executed */
        $this->queryBuilder->map(callback: fn() => null);
    }

    public function testExceptionWhenBindFailure(): void
    {
        /** @Given that I have data to bind */
        $data = ['column1' => 'value1', 'column2' => 'value2'];

        /** @And an error occurs when binding the data */
        $this->statement
            ->method('bindValue')
            ->with('column1', 'value1')
            ->willThrowException(new RuntimeException(message: 'Test exception'));

        /** @Then an exception indicating a binding failure should occur */
        $this->expectException(MySqlFailure::class);

        /** @When the bind method is executed */
        $this->queryBuilder->bind(data: $data);
    }

    public function testExceptionWhenExecuteFailure(): void
    {
        /** @Given that I have a query to execute */
        $sql = 'SELECT * FROM table_test';

        /** @And an error occurs when executing the query */
        $this->statement
            ->method('executeQuery')
            ->willThrowException(new RuntimeException(message: 'Test exception'));

        /** @Then an exception indicating an execution failure should occur */
        $this->expectException(MySqlFailure::class);

        /** @When the execute method is executed */
        $this->queryBuilder->query(sql: $sql)->execute();
    }

    public function testExceptionWhenQueryFailure(): void
    {
        /** @Given that I have a query to prepare */
        $sql = 'SELECT * FROM table_test';

        /** @And an error occurs when preparing the query */
        $this->connection
            ->method('prepare')
            ->with($sql)
            ->willThrowException(new RuntimeException(message: 'Test exception'));

        /** @Then an exception indicating a query preparation failure should occur */
        $this->expectException(MySqlFailure::class);

        /** @When the query method is executed */
        $this->queryBuilder->query(sql: $sql);
    }

    public function testExceptionWhenFetchAllFailure(): void
    {
        /** @Given that I have a result to fetch */
        $this->result
            ->method('fetchAllAssociative')
            ->willThrowException(new RuntimeException(message: 'Test exception'));

        /** @Then an exception indicating a fetch failure should occur */
        $this->expectException(MySqlFailure::class);

        /** @When the fetchAll method is executed */
        $this->queryBuilder->fetchAll();
    }
}
