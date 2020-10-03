<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Listing\Service\ListingDestroyer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingDeleteAction.
 * Handles delete request over /listings/id route.
 * @package App\Action\Listing
 */
final class ListingDeleteAction extends AppAction
{

    /**
     * @var ListingDestroyer $listingDestroyer
     */
    private $listingDestroyer;

    /**
     * ListingDeleteAction constructor.
     * @param ListingDestroyer $listingDestroyer
     */
    public function __construct(ListingDestroyer $listingDestroyer)
    {
        parent::__construct();
        $this->listingDestroyer = $listingDestroyer;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Deletes the listing with the id given through request params.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args Request params
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args)
    : ResponseInterface
    {
        // Collect input from the HTTP request

        if (isset($args['id']) && !empty($args['id']) && $args['id'] > 0) {
            // Invoke the Domain with inputs and retain the result
            if ($this->listingDestroyer->deleteListing($args['id'])) {
                $this->responseData->build('Listing deleted');
                return $this->respond($response, 200);
            }

            $this->responseData->build('Listing could not be deleted');
            return $this->respond($response, 400);
        }

        $this->responseData->build('Listing id is missing');
        return $this->respond($response, 400);
    }

}
