<?php


namespace App\Action\Alerts;


use App\Mail\Mailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AlertAction extends \App\Action\AppAction
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args)
    : ResponseInterface
    {
        if (empty($args['type'])) {
            $this->responseData->build('Parameter is missing');
            return $this->respond($response, 400);
        }
        if ($args['type'] === 'drinks') {
            $this->mailer->sendMail('Azubi', [
                'subject' => 'Azubi-Board: Getränke-Alarm',
                'mail' => 'Bitte den Getränkekühlschrank auffüllen.'
            ]);
            $this->responseData('Alert triggered!');
            return $this->respond($response, 204);
        }
        if ($args['type'] === 'kitchen') {
            $this->mailer->sendMail('Azubi', [
                'subject' => 'Azubi-Board: Küchen-Alarm',
                'mail' => 'Bitte die Küche aufräumen.'
            ]);
            $this->responseData('Alert triggered!');
            return $this->respond($response, 204);
        }
        if ($args['type'] === 'approving') {
            $this->mailer->sendMail('Buchhaltung', [
                'subject' => 'Azubi-Board: Genehmigungs-Alarm',
                'mail' => 'Ein dringendes Element wurder der aktuellen Liste hinzugefügt. Bitte die Liste prüfen.'
            ]);
            $this->responseData('Alert triggered!');
            return $this->respond($response, 204);
        }
        return $this->respond($response, 204);
    }
}
