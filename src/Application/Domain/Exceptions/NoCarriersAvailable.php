<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class NoCarriersAvailable extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'There are no carriers available for dispatch.');
    }
}
