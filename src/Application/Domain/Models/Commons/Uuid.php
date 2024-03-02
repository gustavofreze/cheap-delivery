<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

final readonly class Uuid
{
    public function __construct(private string $value)
    {
    }

    public static function generateV4(): Uuid
    {
        $randomBytes = random_bytes(16);

        $randomBytes[6] = chr(ord($randomBytes[6]) & 0x0f | 0x40);
        $randomBytes[8] = chr(ord($randomBytes[8]) & 0x3f | 0x80);

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomBytes), 4));

        return new Uuid(value: $uuid);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
