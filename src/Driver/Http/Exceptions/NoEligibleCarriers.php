<?php

namespace CheapDelivery\Driver\Http\Exceptions;

use CheapDelivery\Driver\Http\ActionException;
use CheapDelivery\Driver\Http\HttpCode;
use Exception;

final class NoEligibleCarriers extends Exception implements ActionException
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
