<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\EmptyName;

final readonly class Name
{
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new EmptyName();
        }
    }
}
