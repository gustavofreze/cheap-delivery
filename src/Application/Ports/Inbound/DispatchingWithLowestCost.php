<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Ports\Inbound;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;

/**
 * Dispatches a shipment through the carrier that charges the least for it.
 */
interface DispatchingWithLowestCost
{
    /**
     * Processes the given dispatch command.
     *
     * @param DispatchWithLowestCost $command The command carrying the recipient and the product being shipped.
     */
    public function handle(DispatchWithLowestCost $command): void;
}
