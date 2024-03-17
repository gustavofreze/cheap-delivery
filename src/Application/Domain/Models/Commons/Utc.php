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
        $timezone = new DateTimeZone(timezone: self::TIMEZONE);
        $dateTime = new DateTimeImmutable(datetime: 'now', timezone: $timezone);
        $formattedDate = $dateTime->format(format: self::FORMAT_RFC3339);

        /** @var DateTimeImmutable $utc */
        $utc = DateTimeImmutable::createFromFormat(
            format: self::FORMAT_RFC3339,
            datetime: $formattedDate,
            timezone: $timezone
        );

        return new Utc(dateTime: $utc);
    }
}
