<?php

use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/dependencies.php';

/** @var ContainerInterface $container */
$app = AppFactory::createFromContainer($container);

require __DIR__ . '/../src/routes.php';

$app->run();
