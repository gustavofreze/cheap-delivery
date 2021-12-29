<?php

use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require_once __DIR__ . '/../src/dependencies.php');

$app = Bridge::create($containerBuilder->build());

require __DIR__ . '/../src/routes.php';

$app->run();
