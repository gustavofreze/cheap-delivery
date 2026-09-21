<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;

/**
 * Dispatches recorded by the service.
 */
interface Dispatches
{
    /**
     * Records the dispatch together with the facts it produced.
     *
     * @param Dispatch $dispatch The dispatch that was decided.
     */
    public function save(Dispatch $dispatch): void;
}
