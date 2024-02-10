<?php

namespace CheapDelivery;

use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\CalculateShipment;
use CheapDelivery\Driver\Http\Endpoints\CalculateShipment\CalculateShipmentExceptionHandler;
use CheapDelivery\Driver\Http\Middlewares\ErrorHandling;
use CheapDelivery\Query\Shipment\Resource;
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

        $this->app->group('/shipments', function (RouteCollectorProxyInterface $group) {
            $group->get('', Resource::class);
            $group->post('', CalculateShipment::class)->addMiddleware(
                new ErrorHandling(exceptionHandler: new CalculateShipmentExceptionHandler())
            );
        });
    }
}
