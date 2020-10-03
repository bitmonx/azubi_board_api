<?php

use App\Middleware\CorsMiddleware;
use App\Middleware\JwtClaimMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

/**
 * Register middleware
 * @param App $app
 */
return static function (App $app) {
    // Catch exceptions and errors
    $app->add(ErrorMiddleware::class);

    // Handles the correct base path
    $app->add(BasePathMiddleware::class);

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    // Handles CORS (cross origin resource sharing) requests
    $app->add(CorsMiddleware::class);

    // Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Handles authorization through requests
    $app->add(JwtClaimMiddleware::class);
};
