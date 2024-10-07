<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class DispatchId implements Identity, ValueObject
{
    use ValueObjectBehavior;

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
