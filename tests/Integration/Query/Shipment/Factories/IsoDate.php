<?php

namespace Test\Integration\Query\Shipment\Factories;

use DateInterval;
use DateTime;

final class IsoDate extends DateTime
{
    public static function now(): IsoDate
    {
        return new IsoDate('now');
    }

    public function subtract(string $duration): IsoDate
    {
        $this->sub(new DateInterval($duration));
        return $this;
    }

    public function toString(): string
    {
        return $this->format('c');
    }
}
