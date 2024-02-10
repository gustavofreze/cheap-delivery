<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use DateTimeImmutable;
use DateTimeZone;

final readonly class Utc
{
    private const TIMEZONE = 'UTC';
    private const FORMAT_RFC3339 = 'Y-m-d\TH:i:s.u\Z';

    private function __construct(public DateTimeImmutable $dateTime)
    {
    }

    public static function now(): Utc
    {
        $now = new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE));
        $formattedDateTime = $now->format(self::FORMAT_RFC3339);
        $dateTime = DateTimeImmutable::createFromFormat(
            self::FORMAT_RFC3339,
            $formattedDateTime,
            new DateTimeZone(self::TIMEZONE)
        );

        return new Utc(dateTime: $dateTime);
    }
}
