<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Events;

use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;

interface DispatchEvent
{
    /**
     * Builds an EventRecord from the dispatch event.
     *
     * @return EventRecord The EventRecord representing the dispatch event.
     */
    public function build(): EventRecord;
}
