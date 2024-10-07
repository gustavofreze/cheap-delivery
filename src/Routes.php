<?php

declare(strict_types=1);

namespace CheapDelivery;

use CheapDelivery\Driver\Http\Endpoints\Dispatch\DispatchExceptionHandler;
use CheapDelivery\Driver\Http\Endpoints\Dispatch\DispatchWithLowestCost;
use CheapDelivery\Driver\Http\Middlewares\ErrorHandling;
use CheapDelivery\Query\Dispatch\Resource;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Interfaces\RouteCollectorProxyInterface;

final readonly class Routes
{
    public function __construct(private App $app)
    {
        $routeCollector = $this->app->getRouteCollector();
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
    }

    public function build(): void
    {
        $this->app->addErrorMiddleware(true, true, true);
        $this->app->addBodyParsingMiddleware();

        $this->app->group('/dispatches', function (RouteCollectorProxyInterface $group) {
            $group->get('', Resource::class);
            $group->post('', DispatchWithLowestCost::class)->addMiddleware(
                new ErrorHandling(exceptionHandler: new DispatchExceptionHandler())
            );
        });
    }
}
