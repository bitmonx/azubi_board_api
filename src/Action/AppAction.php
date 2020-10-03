<?php


namespace App\Action;

use Psr\Http\Message\ResponseInterface;

/**
 * Class AppAction.
 * Parent class for Actions
 * @package App\Action
 */
class AppAction
{
    /**
     * @var ResponseData $responseData
     */
    protected $responseData;

    /**
     * AppAction constructor.
     */
    public function __construct()
    {
        $this->responseData = new ResponseData();
    }

    /**
     * Builds the response content using its ResponseData attribute
     * and responds it with the given status.
     * @param ResponseInterface $response
     * @param int $status
     * @return ResponseInterface
     */
    protected function respond(ResponseInterface $response,
                               int $status)
    : ResponseInterface
    {
        $response->getBody()->write((string)json_encode($this->responseData));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
