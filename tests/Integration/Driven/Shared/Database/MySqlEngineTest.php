<?php

declare(strict_types=1);

namespace Test\Integration\Driven\Shared\Database;

use CheapDelivery\Driven\Shared\Database\DatabaseConstraint;
use CheapDelivery\Driven\Shared\Database\DatabaseFailure;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use Test\Integration\IntegrationTestCase;

final class MySqlEngineTest extends IntegrationTestCase
{
    public function testReportsAUniqueConstraintViolation(): void
    {
        /** @Given a carrier name that is already registered */
        $connection = $this->get(RelationalConnection::class);

        $sql = 'INSERT INTO carrier (id, name, cost_modality) VALUES (UUID_TO_BIN(:id), :name, :costModality)';
        $bindings = [
            'id'           => '0193b3a1-0000-7000-8000-00000000000a',
            'name'         => 'DHL',
            'costModality' => '{"modality":"Fixed","cost":10.00}'
        ];

        /** @When the same name is inserted again */
        try {
            $connection->execute(sql: $sql, bindings: $bindings);
            self::fail('The unique constraint on the carrier name was not enforced.');
        } catch (DatabaseFailure $failure) {
            /** @Then the failure names the constraint that was violated and keeps the driver cause */
            self::assertTrue($failure->hasViolated(constraint: DatabaseConstraint::UNIQUE));
            self::assertNotNull($failure->getPrevious());
            self::assertSame($failure->getPrevious()->getMessage(), $failure->getMessage());
        }
    }

    public function testReportsAFailureThatViolatedNoConstraint(): void
    {
        /** @Given a statement against a table that does not exist */
        $connection = $this->get(RelationalConnection::class);

        /** @When it is issued */
        try {
            $connection->fetchAll(sql: 'SELECT * FROM table_that_does_not_exist');
            self::fail('The missing table did not fail.');
        } catch (DatabaseFailure $failure) {
            /** @Then the failure names no constraint */
            self::assertFalse($failure->hasViolated(constraint: DatabaseConstraint::UNIQUE));
        }
    }

    public function testWrapsAStatementFailureThatViolatedNoConstraint(): void
    {
        /** @Given a statement against a table that does not exist */
        $connection = $this->get(RelationalConnection::class);

        /** @When it is issued as a write */
        try {
            $connection->execute(sql: 'INSERT INTO table_that_does_not_exist (id) VALUES (1)');
            self::fail('The missing table did not fail.');
        } catch (DatabaseFailure $failure) {
            /** @Then it still arrives as a database failure, naming no constraint */
            self::assertFalse($failure->hasViolated(constraint: DatabaseConstraint::UNIQUE));
        }
    }

    public function testCountsTheRowsAStatementAffected(): void
    {
        /** @Given a recorded dispatch */
        $connection = $this->get(RelationalConnection::class);

        $this->fixtures()->insertDispatch(
            id: '0193b3a1-0000-7000-8000-00000000000b',
            cost: 10.00,
            createdAt: '2026-09-21 10:00:00.000000',
            carrierName: 'DHL'
        );

        /** @When it is deleted */
        $result = $connection->execute(sql: 'DELETE FROM dispatch');

        /** @Then the affected row count is reported */
        self::assertSame(1, $result->affectedRows());
    }

    public function testAnswersAnEmptyRowWhenNothingMatches(): void
    {
        /** @Given no dispatch is recorded */
        $connection = $this->get(RelationalConnection::class);

        /** @When one is looked up */
        $row = $connection->fetchOne(sql: 'SELECT BIN_TO_UUID(id) AS id FROM dispatch LIMIT 1');

        /** @Then the row is empty and maps to nothing */
        self::assertNull($row->getOrNull());
        self::assertNull($row->map(transform: static fn(array $values): string => (string)$values['id'])->getOrNull());
    }

    public function testMapsTheRowItFound(): void
    {
        /** @Given a recorded dispatch */
        $connection = $this->get(RelationalConnection::class);

        $this->fixtures()->insertDispatch(
            id: '0193b3a1-0000-7000-8000-00000000000c',
            cost: 10.00,
            createdAt: '2026-09-21 10:00:00.000000',
            carrierName: 'DHL'
        );

        /** @When it is looked up */
        $row = $connection->fetchOne(sql: 'SELECT BIN_TO_UUID(id) AS id FROM dispatch LIMIT 1');

        /** @Then the row maps to the value it carries */
        self::assertSame(
            '0193b3a1-0000-7000-8000-00000000000c',
            $row->map(transform: static fn(array $values): string => (string)$values['id'])->getOrNull()
        );
    }

    public function testRollsTheWholeUnitBackWhenTheWorkFails(): void
    {
        /** @Given work that records a dispatch and then fails */
        $connection = $this->get(RelationalConnection::class);

        /** @When it runs inside a transaction */
        try {
            $connection->inTransaction(useCase: function (RelationalConnection $transactional): void {
                $transactional->execute(
                    sql: 'INSERT INTO dispatch (id, cost, carrier_name) VALUES (UUID_TO_BIN(:id), :cost, :name)',
                    bindings: [
                        'id'   => '0193b3a1-0000-7000-8000-00000000000d',
                        'cost' => 10.00,
                        'name' => 'DHL'
                    ]
                );
                $transactional->execute(sql: 'INSERT INTO table_that_does_not_exist (id) VALUES (1)');
            });
            self::fail('The failing unit of work did not fail.');
        } catch (DatabaseFailure) {
            /** @Then nothing the unit recorded survives */
            self::assertSame(0, $this->fixtures()->dispatchCountOf(carrierName: 'DHL'));
        }
    }
}
