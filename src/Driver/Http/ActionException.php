<?php

namespace CheapDelivery\Driver\Http;

use Throwable;

interface ActionException extends Throwable
{
    public function getErrors(): array;
}
