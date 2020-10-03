<?php


namespace App\Action\Item;


use App\Action\AppAction;
use App\Domain\Item\Service\ItemDestroyer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ItemDeleteAction
 * Handles delete request over /items/id route.
 * @package App\Action\Item
 */
final class ItemDeleteAction extends AppAction
{
    /**
     * @var ItemDestroyer $destroyer
     */
    private $destroyer;

    /**
     * ItemDeleteAction constructor.
     * @param ItemDestroyer $destroyer
     */
    public function __construct(ItemDestroyer $destroyer)
    {
        parent::__construct();
        $this->destroyer = $destroyer;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Deletes the item with the id given through request params
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args Request params
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response, array $args)
        :ResponseInterface
    {
        if (empty($args) || !isset($args['id'])) {
            $this->responseData->build('Item id is missing');
            $this->respond($response, 400);
        }
        if ($this->destroyer->deleteItem($args['id'])) {
            $this->responseData->build('Item deleted');
            return $this->respond($response, 200);
        }
        $this->responseData->build('Item could not be deleted');
        return $this->respond($response, 400);
    }

}
