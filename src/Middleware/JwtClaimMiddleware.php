<?php


namespace App\Middleware;


use App\Auth\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class JwtClaimMiddleware.
 * Parses data from the JWT
 * @package App\Middleware
 */
final class JwtClaimMiddleware implements MiddlewareInterface
{
    /**
     * @var JwtAuth $jwtAuth
     */
    private $jwtAuth;

    /**
     * The constructor.
     * @param JwtAuth $jwtAuth
     */
    public function __construct(JwtAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * Parses the encoded data within the JWT and stores it
     * in the request attributes.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $authorization = explode(' ', (string)$request->getHeaderLine('Authorization'));
        $type = $authorization[0] ?? '';
        $credentials = $authorization[1] ?? '';
        if ($type === 'Bearer' && $this->jwtAuth->validateToken($credentials)) {
            $parsedToken = $this->jwtAuth->createParsedToken($credentials);
            $request = $request->withAttribute('token', $parsedToken);
            $request = $request->withAttribute('uid', $parsedToken->getClaim('uid'));
            $request = $request->withAttribute('department', $parsedToken->getClaim('department'));
            $request = $request->withAttribute('isTrainee', $parsedToken->getClaim('isTrainee'));

        }
        return $handler->handle($request);
    }
}
