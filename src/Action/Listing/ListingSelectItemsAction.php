<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Item\Service\ItemSelector;
use App\Domain\Listing\Service\ListingCreator;
use App\Domain\Listing\Service\ListingSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingSelectItemsAction.
 * Handles get request over /listings/id/items route.
 * @package App\Action\Listing
 */
final class ListingSelectItemsAction extends AppAction
{

    /**
     * @var ListingSelector $listingSelector
     */
    private $listingSelector;
    /**
     * @var ListingCreator $listingCreator
     */
    private $listingCreator;
    /**
     * @var ItemSelector $itemSelector
     */
    private $itemSelector;

    /**
     * ListingSelectItemsAction constructor.
     * @param ListingSelector $listingSelector
     * @param ItemSelector $itemSelector
     * @param ListingCreator $creator
     */
    public function __construct(
        ListingSelector $listingSelector,
        ItemSelector $itemSelector,
        ListingCreator $creator)
    {
        parent::__construct();
        $this->listingSelector = $listingSelector;
        $this->itemSelector = $itemSelector;
        $this->listingCreator = $creator;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Selects the listing and its items with the id given through request params.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args Request params
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args)
        :ResponseInterface
    {
        if (empty($args) || !isset($args['id'])) {
            $this->responseData->build('Listing id is missing');
            return $this->respond($response, 400);
        }
        if ((int)$args['id'] !== 0) {
            $listing = $this->listingSelector->getListingById($args['id']);
            if ($listing === null) {
                $this->responseData->build('No listing found');
                return $this->respond($response, 200);
            }
        } else {
            $listing = $this->listingSelector->getCurrentListing();
            if ($listing === null) {
                $listingId = $this->listingCreator->createListing($request->getAttribute('uid'));
                $listing = $this->listingSelector->getListingById($listingId);
            }
        }
        $items = $this->itemSelector->getAllItemsByListing($listing->id);
        $listing->items = $items;
        $this->responseData->build('Listing items fetched', $listing);
        return $this->respond($response, 200);
    }
}
