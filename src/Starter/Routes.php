<?php

namespace CheapDelivery\Starter;

use CheapDelivery\Driver\Http\Shipping\CalculateShipping;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Interfaces\RouteCollectorProxyInterface;

final class Routes
{
    public function __construct(private readonly App $app)
    {
        $routeCollector = $this->app->getRouteCollector();
        $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());
    }

    public function build(): void
    {
        $this->app->addErrorMiddleware(true, true, true);
        $this->app->addBodyParsingMiddleware();

        $this->app->group('/shipment', function (RouteCollectorProxyInterface $group) {
            $group->post('', CalculateShipping::class);
        });
    }
}
