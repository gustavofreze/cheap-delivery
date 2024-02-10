<?php

namespace CheapDelivery\Driven\Shipment\Repository;

use CheapDelivery\Application\Domain\Models\Shipment;
use CheapDelivery\Application\Ports\Outbound\ShipmentsRepository;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\OutboxEvent;
use CheapDelivery\Driven\Shipment\OutboxEvent\EventRecordWrapper;

final readonly class Adapter implements ShipmentsRepository
{
    public function __construct(
        private EventRecordWrapper $wrapper,
        private RelationalConnection $connection,
        private OutboxEvent $outboxEvent
    ) {
    }

    public function save(Shipment $shipment): void
    {
        $this->connection->inTransaction(
            function (RelationalConnection $connection) use ($shipment) {
                $connection
                    ->with()
                    ->query(sql: Queries::INSERT_SHIPMENT)
                    ->bind(data: [
                        ':id'      => $shipment->id->getValue(),
                        ':cost'    => $shipment->cost->value,
                        ':carrierName' => $shipment->carrierName->value,
                    ])
                    ->execute();

                #$events = $this->wrapper->pack(shipment: $shipment);
                #$this->outboxEvent->push(events: $events);
            }
        );
    }
}
