<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Class CorsMiddleware.
 * Prepares the response header for CORS requests.
 * @package App\Middleware
 */
final class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Sets the necessary repsonse headers for CORS requests.
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Headers', $requestHeaders ?: '*');

        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
