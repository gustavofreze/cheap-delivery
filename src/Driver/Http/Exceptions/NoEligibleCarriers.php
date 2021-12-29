<?php

namespace CheapDelivery\Driver\Http\Exceptions;

use CheapDelivery\Driver\Http\HttpCode;
use CheapDelivery\Driver\Http\HttpException;
use Exception;

final class NoEligibleCarriers extends Exception implements HttpException
{
    public function __construct()
    {
        parent::__construct(
            message: 'There are no eligible carriers for the shipment',
            code: HttpCode::UNPROCESSABLE_ENTITY
        );
    }

    public function getErrors(): array
    {
        return ['carrier' => $this->message];
    }
}
