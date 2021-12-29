<?php

namespace CheapDelivery\Driver\Http;

use Throwable;

interface HttpException extends Throwable
{
    public function getErrors(): array;
}
