<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Exceptions;

use DomainException;

final class DistanceOutOfRange extends DomainException
{
    public function __construct(float $current, float $maximum)
    {
        $template = 'Distance is out of range. Current <%.2f>, Maximum <%.2f>.';
        parent::__construct(message: sprintf($template, $current, $maximum));
    }
}
