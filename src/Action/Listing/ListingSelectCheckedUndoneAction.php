<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Item\Service\ItemSelector;
use App\Domain\Listing\Service\ListingSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingSelectCheckedUndoneAction.
 * Handle get requests over /listings/undone route
 * @package App\Action\Listing
 */
class ListingSelectCheckedUndoneAction extends AppAction
{

    /**
     * @var ListingSelector  $selector
     */
    private $selector;
    /**
     * @var ItemSelector $itemSelector
     */
    private $itemSelector;

    /**
     * ListingSelectCheckedUndoneAction constructor.
     * @param ListingSelector $selector
     * @param ItemSelector $itemSelector
     */
    public function __construct(ListingSelector $selector, ItemSelector $itemSelector)
    {
        parent::__construct();
        $this->selector = $selector;
        $this->itemSelector = $itemSelector;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Selects the listing and its items, which are checked but not finished yet.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $listings =  $this->selector->getUndoneCheckedListings();
        if (!empty($listings)) {
            foreach ($listings as $id => $listing) {
                $items = $this->itemSelector->getAllItemsByListing($listing->id);
                $listing->items = $items;
            }
        }
        $this->responseData->build('Listings selected', $listings);
        return $this->respond($response, 200);
    }

}
