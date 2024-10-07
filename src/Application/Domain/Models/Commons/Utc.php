<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use DateTimeImmutable;
use DateTimeZone;

final readonly class Utc
{
    private const TIMEZONE = 'UTC';
    private const FORMAT_RFC3339 = 'Y-m-d\TH:i:s.u\Z';

    public function __construct(public DateTimeImmutable $dateTime)
    {
    }

    public static function now(): Utc
    {
        $timezone = new DateTimeZone(self::TIMEZONE);
        $dateTime = new DateTimeImmutable('now', $timezone);
        $formattedDate = $dateTime->format(self::FORMAT_RFC3339);

        /** @var DateTimeImmutable $utc */
        $utc = DateTimeImmutable::createFromFormat(self::FORMAT_RFC3339, $formattedDate, $timezone);

        return new Utc(dateTime: $utc);
    }
}
