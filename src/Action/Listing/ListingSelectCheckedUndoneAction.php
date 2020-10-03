<?php


namespace App\Action\Listing;


use App\Action\AppAction;
use App\Domain\Listing\Service\ListingSelector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ListingSelectCheckedUndoneAction extends AppAction
{

    private $selector;

    public function __construct(ListingSelector $selector)
    {
        parent::__construct();
        $this->selector = $selector;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $listings =  $this->selector->getUndoneCheckedListings();
        $this->responseData->build('Listings selected', $listings);
        return $this->respond($response, 200);
    }

}
