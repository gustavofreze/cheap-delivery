<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Exceptions;

use RuntimeException;
use Throwable;

final class CarrierModalityNotSupported extends RuntimeException
{
    public function __construct(Throwable $cause)
    {
        $message = 'A registered carrier declares a cost modality this service does not support.';

        parent::__construct(message: $message, previous: $cause);
    }
}
