<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks;

final class Exceptions
{
    public const float UNKNOWN_ERROR = 1000.01;
    public const float NO_ELIGIBLE_CARRIERS = 1000.02;
    public const float NO_CARRIERS_AVAILABLE = 1000.03;
    public const float DISTANCE_OUT_OF_RANGE = 50000.00;
}
