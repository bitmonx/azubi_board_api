<?php

namespace App\Action\Item;

use App\Action\AppAction;
use App\Domain\Item\Service\ItemSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ItemSelectAction.
 * Handles get request over /items[/id] routes.
 * @package App\Action\Item
 */
final class ItemSelectAction extends AppAction
{
    /**
     * @var ItemSelector $selector
     */
    private $selector;

    /**
     * ItemSelectAction constructor.
     * @param ItemSelector $selector
     */
    public function __construct(ItemSelector $selector)
    {
        parent::__construct();
        $this->selector = $selector;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Selects the item(s) with the id given through request params.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args Request params
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        if (empty($args) || !isset($args['id'])) {
            $items = $this->selector->getAllItems();
            $this->responseData->build('All items selected', $items);
            return $this->respond($response, 200);
        }
        if ((int)$args['id'] === 0) {
            $this->responseData->build('Invalid id');
            return $this->respond($response, 400);
        }
        $item = $this->selector->getItemById((int)$args['id']);
        $this->responseData->build('Item selected', $item);
        return $this->respond($response, 200);
    }
}
