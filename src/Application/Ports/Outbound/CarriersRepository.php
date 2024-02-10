<?php

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Carriers;

interface CarriersRepository
{
    /**
     * Retrieves all carriers from the repository.
     *
     * @return Carriers The collection of carriers.
     */
    public function findAll(): Carriers;
}
