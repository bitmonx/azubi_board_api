<?php

namespace App\Action\Item;

use App\Action\AppAction;
use App\Domain\Item\Service\ItemCreator;
use App\Domain\Item\Service\ItemSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ItemCreateAction.
 * Handles post request over /items route.
 * @package App\Action\Item
 */
final class ItemCreateAction extends AppAction
{
    /**
     * @var ItemCreator $creator
     */
    private $creator;
    /**
     * @var ItemSelector $selector
     */
    private $selector;

    /**
     * ItemCreateAction constructor.
     * @param ItemCreator $creator
     * @param ItemSelector $selector
     */
    public function __construct(ItemCreator $creator, ItemSelector $selector)
    {
        parent::__construct();
        $this->creator = $creator;
        $this->selector = $selector;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Creates an item using the incoming data
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = $request->getParsedBody();
        if (!isset($data['description'], $data['deadline'], $data['listing_id']) ||
            empty($data['description']) || empty($data['deadline']) || empty($data['listing_id'])) {
            $this->responseData->build('Data is missing');
            return $this->respond($response, 400);
        }
        $itemId = $this->creator->addItem(
            $data['listing_id'],
            $data['description'],
            $request->getAttribute('uid'),
            $data['deadline']);
        $item = $this->selector->getItemById($itemId);
        $this->responseData->build('Item created', $item);
        return $this->respond($response, 200);
    }
}
