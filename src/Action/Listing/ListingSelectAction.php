<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Listing\Service\ListingCreator;
use App\Domain\Listing\Service\ListingSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingSelectAction.
 * Handles get request over /listings/[id] route.
 * @package App\Action\Listing
 */
final class ListingSelectAction extends AppAction
{
    /**
     * @var ListingSelector $selector
     */
    private $selector;
    /**
     * @var ListingCreator $creator
     */
    private $creator;

    /**
     * ListingSelectAction constructor.
     * @param ListingSelector $selector
     * @param ListingCreator $creator
     */
    public function __construct(ListingSelector $selector, ListingCreator $creator)
    {
        parent::__construct();
        $this->selector = $selector;
        $this->creator = $creator;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Selects the listing(s) with the id given through request params.
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
            $listings = $this->selector->getAllListings();
            $this->responseData->build('All listings selected', $listings);
            return $this->respond($response, 200);
        }
        if ((int)$args['id'] === 0) {
            $currentListing = $this->selector->getCurrentListing();
            if ($currentListing === null) {
                $listingId = $this->creator->createListing($request->getAttribute('uid'));
                $currentListing = $this->selector->getListingById($listingId);
            }
            $this->responseData->build('Current listing selected', $currentListing);
            return $this->respond($response, 200);
        }
        $listing = $this->selector->getListingById((int)$args['id']);
        $this->responseData->build('Listing selected', $listing);
        return $this->respond($response, 200);
    }
}
