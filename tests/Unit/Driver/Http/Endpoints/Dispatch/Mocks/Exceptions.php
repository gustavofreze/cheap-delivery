<?php

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks;

final class Exceptions
{
    public const UNKNOWN_ERROR = 1000.01;
    public const NO_ELIGIBLE_CARRIERS = 1000.02;
    public const NO_CARRIERS_AVAILABLE = 1000.03;
    public const DISTANCE_OUT_OF_RANGE = 50000.00;
}
