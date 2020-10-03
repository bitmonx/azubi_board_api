<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Listing\Service\ListingModifier;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingFinishingAction.
 * Handle put request over /listings/id/finish route.
 * @package App\Action\Listing
 */
final class ListingFinishingAction extends AppAction
{

    /**
     * @var ListingModifier $modifier
     */
    private $modifier;

    /**
     * ListingFinishingAction constructor.
     * @param ListingModifier $modifier
     */
    public function __construct(ListingModifier $modifier)
    {
        parent::__construct();
        $this->modifier = $modifier;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Finishes the listing with the id given through request params.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args Request params
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        if (!isset($args['id']) || empty($args['id']) || $args['id'] === 0) {
            $this->responseData->build('Listing id is missing');
            return $this->respond($response, 400);
        }

        $userId = $request->getAttribute('uid');
        $listingId = $args['id'];
        if ($this->modifier->finishListing($listingId, $userId)) {
            $this->responseData->build('Listing finished');
            return $this->respond($response, 200);
        }

        $this->responseData->build('Listing could not be finished');
        return $this->respond($response, 400);
    }
}
