<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use Ramsey\Uuid\Uuid as RamseyUuid;

final readonly class Uuid
{
    public function __construct(private string $value)
    {
    }

    public static function generateV4(): Uuid
    {
        return new Uuid(value: RamseyUuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->value;
    }
}
