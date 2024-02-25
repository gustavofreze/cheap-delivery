<?php

namespace CheapDelivery\Driver\Http\Endpoints\CalculateShipment\Mocks;

use CheapDelivery\Application\Commands\CalculateShipment;
use CheapDelivery\Application\Commands\Command;
use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use RuntimeException;

final class CalculateShipmentHandlerMock implements CommandHandler
{
    public CalculateShipment $lastCommand;

    public function handle(Command|CalculateShipment $command): void
    {
        $this->lastCommand = $command;

        match ($command->person->distance->value) {
            Exceptions::UNKNOWN_ERROR => throw new RuntimeException('Any error.'),
            Exceptions::NO_ELIGIBLE_CARRIERS => throw new NoEligibleCarriers(),
            Exceptions::NO_CARRIERS_AVAILABLE => throw new NoCarriersAvailable(),
            default => null
        };
    }
}
