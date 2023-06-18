<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;

final class Name
{
    public function __construct(private readonly string $value)
    {
        if (empty($this->value)) {
            throw new LogicException(message: 'Name cannot be empty.');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
