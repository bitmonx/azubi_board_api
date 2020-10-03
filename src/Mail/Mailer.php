<?php


namespace App\Mail;


use App\Domain\User\Service\UserSelector;

/**
 * Class Mailer.
 * Sends Emails
 * @package App\Mail
 */
final class Mailer
{
    /**
     * @var string $sender
     */
    private $sender;
    /**
     * @var UserSelector $userSelector
     */
    private $userSelector;

    /**
     * Mailer constructor.
     * @param string $sender
     * @param UserSelector $userSelector
     */
    public function __construct(string $sender, UserSelector $userSelector)
    {
        $this->sender = $sender;
        $this->userSelector = $userSelector;
    }

    /**
     * Sends emails to the email addresses matching the given
     * param.
     * @param string $recipients
     * @param array $mail
     */
    public function sendMail(string $recipients, array $mail): void
    {
        $headers = "From: " . $this->sender . "\r\n" .
            "Reply-To: " . $this->sender . "\r\n" .
            "X-Mailer: PHP/" . PHP_VERSION;

        if (!empty($recipients) && $recipients === 'Azubi') {
            $emails = $this->userSelector->getAllTraineeEmails();
            $to = implode(', ', $emails);
        } else {
            $emails = $this->userSelector->getAllEmailsByDepartment($recipients);
            if (empty($emails)) {
                return;
            }

            $emails = $this->userSelector->getAllTraineeEmails();
            $to = implode(', ', $emails);
        }

        mail($to, $mail['subject'], $mail['message'], $headers);
    }
}
