<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use InvalidArgumentException;

final class Revision
{
    public function __construct(public int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(message: 'Revision cannot be zero or negative.');
        }
    }
}
