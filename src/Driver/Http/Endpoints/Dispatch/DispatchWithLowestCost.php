<?php

declare(strict_types=1);

namespace CheapDelivery\Driver\Http\Endpoints\Dispatch;

use CheapDelivery\Application\Ports\Inbound\DispatchingWithLowestCost;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TinyBlocks\Http\Server\Response;

final readonly class DispatchWithLowestCost implements RequestHandlerInterface
{
    public function __construct(private DispatchingWithLowestCost $dispatching)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $payload = json_decode($request->getBody()->__toString(), true);
        $command = new Request(payload: (array)$payload)->toCommand();

        $this->dispatching->handle(command: $command);

        return Response::created(body: ['id' => $command->id->identityValue()]);
    }
}
