<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Repository;

use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;
use CheapDelivery\Application\Ports\Outbound\Dispatches;
use CheapDelivery\Driven\Dispatch\Repository\Records\DispatchRecordWriter;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use TinyBlocks\Outbox\OutboxRepository;

final readonly class DispatchRepository implements Dispatches
{
    public function __construct(
        private OutboxRepository $outbox,
        private DispatchRecordWriter $writer,
        private RelationalConnection $connection
    ) {
    }

    public function save(Dispatch $dispatch): void
    {
        $this->connection->inTransaction(useCase: function () use ($dispatch): void {
            $this->writer->insert(dispatch: $dispatch);

            $this->outbox->push(records: $dispatch->peekEvents());
        });

        $dispatch->pullEvents();
    }
}
