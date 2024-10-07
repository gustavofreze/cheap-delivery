<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Handlers;

use CheapDelivery\Application\Commands\Command;
use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use CheapDelivery\Application\Ports\Outbound\Dispatches;

final readonly class DispatchWithLowestCostHandler implements CommandHandler
{
    public function __construct(private Carriers $carriers, private Dispatches $dispatches)
    {
    }

    public function handle(Command|DispatchWithLowestCost $command): void
    {
        $carriers = $this->carriers->findAll();

        /** @var DispatchWithLowestCost $command */
        $person = $command->person;
        $product = $command->product;

        $dispatch = Dispatch::create();
        $dispatch->dispatchWithLowestCost(
            weight: $product->weight,
            distance: $person->distance,
            carriers: $carriers
        );

        $this->dispatches->save(dispatch: $dispatch);
    }
}
