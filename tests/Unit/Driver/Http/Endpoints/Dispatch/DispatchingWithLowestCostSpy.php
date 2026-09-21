<?php

declare(strict_types=1);

namespace Test\Unit\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Ports\Inbound\DispatchingWithLowestCost;
use Throwable;

final class DispatchingWithLowestCostSpy implements DispatchingWithLowestCost
{
    public ?DispatchWithLowestCost $received = null;

    public function __construct(private readonly ?Throwable $failure = null)
    {
    }

    public function handle(DispatchWithLowestCost $command): void
    {
        $this->received = $command;

        if (!is_null($this->failure)) {
            throw $this->failure;
        }
    }
}
