<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Driver\Http\Endpoints\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TinyBlocks\Http\HttpResponse;

final class CalculateShipmentExceptionHandler implements ExceptionHandler
{
    public function handle(Throwable $exception): ResponseInterface
    {
        $error = ['error' => $exception->getMessage()];

        return match (get_class($exception)) {
            NoEligibleCarriers::class => HttpResponse::badRequest(data: $error),
            NoCarriersAvailable::class => HttpResponse::notFound(data: $error),
            default => HttpResponse::internalServerError(data: $error)
        };
    }
}
