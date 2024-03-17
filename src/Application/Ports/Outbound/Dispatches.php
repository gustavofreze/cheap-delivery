<?php

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Dispatch;

interface Dispatches
{
    /**
     * Saves the dispatch information.
     *
     * @param Dispatch $dispatch The dispatch information to save.
     * @return void
     */
    public function save(Dispatch $dispatch): void;
}
