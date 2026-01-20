<?php

declare(strict_types=1);

namespace Test\Integration\Application\Handlers\Factories;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Application\Domain\Models\DispatchId;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Shipment;
use DateTimeImmutable;
use Test\Integration\QueryCapabilities;

final class QueryAdapter extends QueryCapabilities
{
    private const string FIND_LAST_DISPATCH = '
            SELECT BIN_TO_UUID(id) AS id, cost, carrier_name
            FROM dispatch
            ORDER BY created_at DESC';

    private const string FIND_EVENT = '
                  SELECT snapshot, revision, occurred_on
                  FROM outbox_event
                  WHERE aggregate_id = :aggregateId
                    AND event_type = :eventType;';

    public function findLastDispatch(): Dispatch
    {
        $record = $this->connection
                      ->executeQuery(self::FIND_LAST_DISPATCH)
                      ->fetchAllAssociative()[0];

        return new Dispatch(
            id: new DispatchId(value: new Uuid(value: $record['id'])),
            shipment: Shipment::from(
                cost: new Cost(value: (float)$record['cost']),
                carrierName: new Name(value: $record['carrier_name'])
            )
        );
    }

    public function findEventBy(Identity $aggregateId, string $eventType): DispatchedWithLowestCost
    {
        $record = $this
                      ->connection
                      ->executeQuery(self::FIND_EVENT, [
                          'eventType'   => $eventType,
                          'aggregateId' => $aggregateId->getValue()
                      ])
                      ->fetchAllAssociative()[0];

        $snapshot = json_decode($record['snapshot'], true);
        $dispatch = new Dispatch(
            id: new DispatchId(value: new Uuid(value: $snapshot['id'])),
            shipment: Shipment::from(
                cost: new Cost(value: (float)$snapshot['shipment']['cost']),
                carrierName: new Name(value: $snapshot['shipment']['carrierName'])
            )
        );

        return new DispatchedWithLowestCost(
            id: $dispatch->id,
            dispatch: $dispatch,
            instant: new Utc(dateTime: new DateTimeImmutable($record['occurred_on']))
        );
    }
}
