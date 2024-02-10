<?php

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class NoCarriersAvailable extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'No carriers available for shipment.');
    }
}
