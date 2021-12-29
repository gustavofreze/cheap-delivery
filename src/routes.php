<?php

declare(strict_types=1);

namespace CheapDelivery;

use CheapDelivery\Driver\Http\Actions\Home\Home;
use CheapDelivery\Driver\Http\Actions\Shipping\CalculateShipping;
use CheapDelivery\Driver\Http\Middleware\ErrorToJsonResponse;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

/** @var App $app */
$app->addMiddleware($app->getContainer()->get(ErrorToJsonResponse::class));
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->group('/', function (RouteCollectorProxyInterface $group) {
    $group->any('', Home::class);
});

$app->group('/shipment', function (RouteCollectorProxyInterface $group) {
    $group->post('', CalculateShipping::class);
});
