<?php


namespace App\Action;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PreflightAction.
 * Handles CORS options requests
 * @package App\Action
 */
final class PreflightAction extends AppAction
{

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Responds always with status 200.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response)
    : ResponseInterface
    {
        return $this->respond($response, 200);
    }

}
