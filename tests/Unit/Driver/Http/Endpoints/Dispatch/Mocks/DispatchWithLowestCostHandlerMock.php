<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch\Mocks;

use CheapDelivery\Application\Commands\Command;
use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use RuntimeException;

final class DispatchWithLowestCostHandlerMock implements CommandHandler
{
    public DispatchWithLowestCost $lastCommand;

    public function handle(Command|DispatchWithLowestCost $command): void
    {
        /** @var DispatchWithLowestCost $command */
        $this->lastCommand = $command;
        $distance = $command->person->distance->value;

        match ($distance) {
            Exceptions::UNKNOWN_ERROR         => throw new RuntimeException(message: 'Any error.'),
            Exceptions::NO_ELIGIBLE_CARRIERS  => throw new NoEligibleCarriers(),
            Exceptions::DISTANCE_OUT_OF_RANGE => throw new DistanceOutOfRange(current: $distance, maximum: 20000.00),
            Exceptions::NO_CARRIERS_AVAILABLE => throw new NoCarriersAvailable(),
            default                           => null
        };
    }
}
