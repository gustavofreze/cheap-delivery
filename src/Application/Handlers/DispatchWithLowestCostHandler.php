<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Handlers;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Dispatch\Dispatch;
use CheapDelivery\Application\Ports\Inbound\DispatchingWithLowestCost;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use CheapDelivery\Application\Ports\Outbound\Dispatches;

final readonly class DispatchWithLowestCostHandler implements DispatchingWithLowestCost
{
    public function __construct(private Carriers $carriers, private Dispatches $dispatches)
    {
    }

    public function handle(DispatchWithLowestCost $command): void
    {
        $dispatch = Dispatch::dispatchWithLowestCost(
            id: $command->id,
            weight: $command->product->weight,
            carriers: $this->carriers->findAll(),
            distance: $command->person->distance
        );

        $this->dispatches->save(dispatch: $dispatch);
    }
}
