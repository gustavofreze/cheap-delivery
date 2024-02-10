<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use InvalidArgumentException;

final class AggregateType
{
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException(message: 'Aggregate type cannot be empty.');
        }
    }
}
