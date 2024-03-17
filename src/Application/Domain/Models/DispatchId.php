<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;

final class DispatchId implements Identity
{
    public function __construct(public Uuid $value)
    {
    }

    public static function create(?Uuid $value = null): DispatchId
    {
        if (is_null($value)) {
            $value = Uuid::generateV4();
        }

        return new DispatchId(value: $value);
    }

    public function getValue(): string
    {
        return $this->value->toString();
    }
}
