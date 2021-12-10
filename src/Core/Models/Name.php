<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

use LogicException;

final class Name
{
    public function __construct(private string $value)
    {
        if (empty($this->value)) {
            throw new LogicException('Name cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
