<?php

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Carriers as CarriersCollection;
use TinyBlocks\Collection\Collectible;

interface Carriers
{
    /**
     * Retrieves all carriers from the repository.
     *
     * @return CarriersCollection|Collectible The collection of carriers.
     */
    public function findAll(): CarriersCollection|Collectible;
}
