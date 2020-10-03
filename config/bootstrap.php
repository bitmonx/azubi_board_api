<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

//Builder for dependecy injection container
$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Build dependecy injection container instance
$container = $containerBuilder->build();

// Create App instance
$app = $container->get(App::class);

// Register routes
(require __DIR__ . '/routes.php')($app);

// Register middleware
(require __DIR__ . '/middleware.php')($app);

return $app;
