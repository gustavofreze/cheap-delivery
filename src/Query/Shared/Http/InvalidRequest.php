<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Shared\Http;

use InvalidArgumentException;

final class InvalidRequest extends InvalidArgumentException
{
    public function __construct(public readonly string $reason)
    {
    }
}
