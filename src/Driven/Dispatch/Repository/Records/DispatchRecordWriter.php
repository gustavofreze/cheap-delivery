<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Repository\Records;

use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;
use CheapDelivery\Driven\Dispatch\Repository\Queries;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;

final readonly class DispatchRecordWriter
{
    public function __construct(private RelationalConnection $connection)
    {
    }

    public function insert(Dispatch $dispatch): void
    {
        $this->connection->execute(sql: Queries::INSERT, bindings: [
            'id'          => $dispatch->id->identityValue(),
            'cost'        => $dispatch->shipment->cost->toFloat(),
            'carrierName' => $dispatch->shipment->carrierName->value
        ]);
    }
}
