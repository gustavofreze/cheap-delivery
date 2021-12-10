<?php

declare(strict_types=1);

namespace CheapDelivery;

/** @var App $app */

use CheapDelivery\Driver\Http\Shipping\CalculateShipping;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

$app->group('/shipment', function (RouteCollectorProxyInterface $group) {
    $group->post('', CalculateShipping::class);
});
