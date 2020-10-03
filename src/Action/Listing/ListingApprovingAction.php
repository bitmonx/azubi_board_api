<?php

namespace App\Action\Listing;

use App\Action\AppAction;
use App\Domain\Listing\Service\ListingModifier;
use App\Mail\Mailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ListingApprovingAction.
 * Handles put request over /listings/id/approve route.
 * @package App\Action\Listing
 */
final class ListingApprovingAction extends AppAction
{

    /**
     * @var ListingModifier $modifier
     */
    private $modifier;
    /**
     * @var Mailer $mailer
     */
    private $mailer;

    /**
     * ListingApprovingAction constructor.
     * @param ListingModifier $modifier
     * @param Mailer $mailer
     */
    public function __construct(ListingModifier $modifier, Mailer $mailer)
    {
        parent::__construct();
        $this->modifier = $modifier;
        $this->mailer = $mailer;
    }

    /**
     * Automatically called function of callable classes.
     * Handles the request by processing the incoming data
     * and replying a built response.
     * Approves the listing with the id given through request params.
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
        if ($this->modifier->approveListing($listingId, $userId)) {
            $this->mailer->sendMail('Azubi', [
                'subject' => 'AzubiBoard: Liste freigegeben',
                'message' => 'Die aktuelle Liste wurde freigegeben. Bitte abschließen.'
            ]);
            $this->responseData->build('Listing approved');
            return $this->respond($response, 200);
            // Build the HTTP response
        }

        $this->responseData->build('Listing could not be approved');
        return $this->respond($response, 400);
    }
}
