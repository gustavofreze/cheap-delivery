<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use InvalidArgumentException;

final readonly class Uuid
{
    private const UUID_PATTERN = '/^[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}$/i';
    private const RANDOM_BYTES_SIZE = 16;

    public function __construct(private string $value)
    {
        if (!self::isUuid($value)) {
            $template = 'Invalid UUID <%s>.';
            throw new InvalidArgumentException(message: sprintf($template, $this->value));
        }
    }

    public static function generateV4(): Uuid
    {
        $randomBytes = random_bytes(self::RANDOM_BYTES_SIZE);

        $randomBytes[6] = chr(ord($randomBytes[6]) & 0x0f | 0x40);
        $randomBytes[8] = chr(ord($randomBytes[8]) & 0x3f | 0x80);

        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomBytes), 4));

        return new Uuid(value: $uuid);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public static function isUuid(string $value): bool
    {
        return preg_match(self::UUID_PATTERN, $value) === 1;
    }
}
