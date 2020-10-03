<?php


namespace App\Action\Item;


use App\Action\AppAction;
use App\Domain\Item\Service\ItemModifier;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ItemEditAction.
 * Handles put request over /items/id route.
 * @package App\Action\Item
 */
final class ItemEditAction extends AppAction
{
    /**
     * @var ItemModifier $modifier
     */
    private $modifier;

    /**
     * ItemEditAction constructor.
     * @param ItemModifier $modifier
     */
    public function __construct(ItemModifier $modifier)
    {
        parent::__construct();
        $this->modifier = $modifier;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Edits the item with the id given through request params
     * using the incoming data.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args)
        :ResponseInterface
    {
        $data = $request->getParsedBody();
        if (!isset($data['description'], $data['deadline'], $args['id']) ||
            empty($data['description']) || empty($data['deadline']) || empty($args['id'])) {
            $this->responseData->build('Data is missing');
            return $this->respond($response, 400);
        }
        if ($this->modifier->editItem($args['id'], $data['description'], $data['deadline'])) {
            $this->responseData->build('Item edited');
            return $this->respond($response, 200);
        }
        $this->responseData->build('Item could not be edited');
        return $this->respond($response, 400);
    }
}
