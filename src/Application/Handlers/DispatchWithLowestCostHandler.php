<?php

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

    /**
     * @param DispatchWithLowestCost $command
     * @return void
     */
    public function handle(Command $command): void
    {
        $carriers = $this->carriers->findAll();

        $dispatch = Dispatch::create();
        $dispatch->dispatchWithLowestCost(
            weight: $command->product->weight,
            distance: $command->person->distance,
            carriers: $carriers
        );

        $this->dispatches->save(dispatch: $dispatch);
    }
}
