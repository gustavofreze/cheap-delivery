<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class EmptyName extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Name cannot be empty.');
    }
}
