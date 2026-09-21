<?php

declare(strict_types=1);

namespace Test\Integration;

use Doctrine\DBAL\Connection;

final readonly class Fixtures
{
    private function __construct(private Connection $connection)
    {
    }

    public static function from(Connection $connection): Fixtures
    {
        return new Fixtures(connection: $connection);
    }

    public function purgeAll(): void
    {
        $this->truncateDispatches();
        $this->truncateOutboxEvents();
    }

    public function truncateCarriers(): void
    {
        $this->connection->executeStatement(sql: 'DELETE FROM carrier');
    }

    public function truncateDispatches(): void
    {
        $this->connection->executeStatement(sql: 'DELETE FROM dispatch');
    }

    public function truncateOutboxEvents(): void
    {
        $this->connection->executeStatement(sql: 'DELETE FROM outbox_events');
    }

    public function insertCarrier(string $id, string $name, string $costModality): void
    {
        $this->connection->executeStatement(
            sql: '
                INSERT INTO carrier (id, name, cost_modality)
                VALUES (UUID_TO_BIN(:id), :name, :costModality)
            ',
            params: [
                'id'           => $id,
                'name'         => $name,
                'costModality' => $costModality
            ]
        );
    }

    public function insertDispatch(string $id, float $cost, string $createdAt, string $carrierName): void
    {
        $this->connection->executeStatement(
            sql: '
                INSERT INTO dispatch (id, cost, carrier_name, created_at)
                VALUES (UUID_TO_BIN(:id), :cost, :carrierName, :createdAt)
            ',
            params: [
                'id'          => $id,
                'cost'        => $cost,
                'createdAt'   => $createdAt,
                'carrierName' => $carrierName
            ]
        );
    }

    public function dispatchCountOf(string $carrierName): int
    {
        $count = $this->connection->fetchOne(
            query: '
                SELECT COUNT(*) AS total
                FROM dispatch AS dsp
                WHERE dsp.carrier_name = :carrierName
            ',
            params: ['carrierName' => $carrierName]
        );

        return (int)$count;
    }

    public function outboxPayloadOf(string $eventType, string $dispatchId): array
    {
        $payload = $this->connection->fetchOne(
            query: '
                SELECT evt.payload
                FROM outbox_events AS evt
                WHERE evt.aggregate_id = UUID_TO_BIN(:dispatchId)
                  AND evt.event_type = :eventType
            ',
            params: [
                'eventType'  => $eventType,
                'dispatchId' => $dispatchId
            ]
        );

        return (array)json_decode((string)$payload, true);
    }

    public function outboxEventTypesOf(string $dispatchId): array
    {
        return $this->connection->fetchFirstColumn(
            query: '
                SELECT evt.event_type
                FROM outbox_events AS evt
                WHERE evt.aggregate_id = UUID_TO_BIN(:dispatchId)
                ORDER BY evt.aggregate_version
            ',
            params: ['dispatchId' => $dispatchId]
        );
    }
}
