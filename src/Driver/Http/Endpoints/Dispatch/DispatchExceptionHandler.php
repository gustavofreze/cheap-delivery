<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\EmptyName;
use CheapDelivery\Application\Domain\Exceptions\NameTooLong;
use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Driver\Http\Endpoints\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TinyBlocks\Http\Response;

final class DispatchExceptionHandler implements ExceptionHandler
{
    public function handle(Throwable $exception): ResponseInterface
    {
        $error = ['error' => $exception->getMessage()];

        return match (get_class($exception)) {
            EmptyName::class,
            NameTooLong::class,
            WeightOutOfRange::class,
            NonPositiveValue::class,
            DistanceOutOfRange::class  => Response::unprocessableEntity(body: $error),
            NoEligibleCarriers::class  => Response::badRequest(body: $error),
            NoCarriersAvailable::class => Response::notFound(body: $error),
            default                    => Response::internalServerError(body: $error)
        };
    }
}
