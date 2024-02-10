<?php

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class NoEligibleCarriers extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'There are no eligible carriers for the shipment.');
    }
}
