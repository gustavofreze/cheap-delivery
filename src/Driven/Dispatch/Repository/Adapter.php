<?php

namespace CheapDelivery\Driven\Dispatch\Repository;

use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Application\Ports\Outbound\Dispatches;
use CheapDelivery\Driven\Dispatch\OutboxEvent\EventRecordWrapper;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\OutboxEvent;

final readonly class Adapter implements Dispatches
{
    public function __construct(
        private EventRecordWrapper $wrapper,
        private RelationalConnection $connection,
        private OutboxEvent $outboxEvent
    ) {
    }

    public function save(Dispatch $dispatch): void
    {
        $this->connection->inTransaction(
            useCase: function (RelationalConnection $connection) use ($dispatch) {
                $connection
                    ->with()
                    ->query(sql: Queries::INSERT_DISPATCH)
                    ->bind(data: [
                        ':id'          => $dispatch->id->getValue(),
                        ':cost'        => $dispatch->shipment?->cost->value,
                        ':carrierName' => $dispatch->shipment?->carrierName->value,
                    ])
                    ->execute();

                $events = $this->wrapper->pack(dispatch: $dispatch);
                $this->outboxEvent->push(events: $events);
            }
        );
    }
}
