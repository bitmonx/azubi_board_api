<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Listing\Service\ListingCreator;
use App\Domain\Listing\Service\ListingSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingCreateAction.
 * Handles post request over /listings route.
 * @package App\Action\Listing
 */
final class ListingCreateAction extends AppAction
{
    /**
     * @var ListingCreator $listingCreator
     */
    private $listingCreator;
    /**
     * @var ListingSelector $listingSelector
     */
    private $listingSelector;

    /**
     * ListingCreateAction constructor.
     * @param ListingCreator $listingCreator
     * @param ListingSelector $listingSelector
     */
    public function __construct(
        ListingCreator $listingCreator,
        ListingSelector $listingSelector)
    {
        parent::__construct();
        $this->listingCreator = $listingCreator;
        $this->listingSelector = $listingSelector;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Creates a listing and replies it.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $listingId = $this->listingCreator->createListing($request->getAttribute('uid'));
        $listing = $this->listingSelector->getListingById($listingId);

        $this->responseData->build('Listing created!', $listing);
        return $this->respond($response, 201);

    }
}
