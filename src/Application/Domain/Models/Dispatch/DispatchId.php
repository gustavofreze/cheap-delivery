<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Models\Commons\AggregateIdentity;
use CheapDelivery\Application\Domain\Models\Commons\UniqueIdentifier;
use CheapDelivery\Application\Domain\Models\Commons\ValueObject;
use CheapDelivery\Application\Domain\Models\Commons\ValueObjectBehavior;

final readonly class DispatchId implements AggregateIdentity, ValueObject
{
    use ValueObjectBehavior;

    public function __construct(private string $value)
    {
    }

    public static function generate(): DispatchId
    {
        return new DispatchId(value: UniqueIdentifier::generate()->toString());
    }

    public function identityValue(): string
    {
        return $this->value;
    }
}
