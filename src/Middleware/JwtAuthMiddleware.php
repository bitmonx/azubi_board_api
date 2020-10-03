<?php


namespace App\Middleware;


use App\Auth\JwtAuth;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class JwtAuthMiddleware.
 * Checks authorization.
 * @package App\Middleware
 */
final class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var JwtAuth $jwtAuth
     */
    private $jwtAuth;

    /**
     * @var ResponseFactoryInterface $responseFactory
     */
    private $responseFactory;

    /**
     * The constructor.
     **
     * @param JwtAuth $jwtAuth
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        JwtAuth $jwtAuth,
        ResponseFactoryInterface $responseFactory
    )
    {
        $this->jwtAuth = $jwtAuth;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Receives access token within the request and
     * checks whether the request is authorized for the route or not.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $token = $request->getAttribute('token');
        $route = $request->getUri()->getPath();
        $department = $request->getAttribute('department');
        $isTrainee = $request->getAttribute('isTrainee');

        if (!$token || !$this->jwtAuth->validateToken($token)) {
            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }
        if (empty($department || empty($isTrainee))) {
            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }
        if ($department !== 'Softwareentwicklung' && strpos($route, '/listings/approve/') !== false) {
            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }
        if (!$isTrainee && strpos($route, '/listings/finish/') !== false) {
            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }
        return $handler->handle($request);
    }
}
