<?php

namespace App\Services;

use App\Messages\EmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $adminMail;

    /**
     * @var string
     */
    private $mailConf;

    /**
     * @var string
     */
    private $telegramConf;
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * NotificationService constructor.
     *
     * @param string              $adminMail
     * @param string              $mailConf
     * @param string              $telegramConf
     * @param MessageBusInterface $bus
     */
    public function __construct(string $adminMail,
                                string $mailConf,
                                string $telegramConf,
                                MessageBusInterface $bus)
    {
        $this->adminMail = $adminMail;
        $this->mailConf = $mailConf;
        $this->telegramConf = $telegramConf;
        $this->bus = $bus;
    }

    /**
     * @param string $title
     * @param string $body
     * @param array  $method
     */
    public function notify(string $title, string $body, array $method = [])
    {
        in_array('email', $method) && $this->mailConf ? $this->sendEmail($title, $body) : true;
        in_array('telegram', $method) && $this->telegramConf ? $this->telegramMail($title) : true;
    }

    /**
     * @param string $title
     * @param string $body
     *
     * @return bool
     */
    public function sendEmail(string $title, string $body): bool
    {
        $this->bus->dispatch(new EmailMessage($this->adminMail, 'vgoogle@i.ua', $title, $body));

//        $message = (new \Swift_Message($email->getTitle()))
//            ->setFrom($email->getFrom())
//            ->setTo($email->getTo())
//            ->setBody($email->getBody(), 'text/html');
//
//        try {
//            $this->mailer->send($message);
//        } catch (\Swift_TransportException $e) {
//            echo $e->getMessage();
//            return false;
//        } catch (\Exception $e) {
//            echo $e->getMessage();
//            return false;
//        }
//        echo 'sent';
        return true;
    }

    /**
     * @param $body
     *
     * @return bool
     */
    public function telegramMail($body)
    {
        //todo send telegramMail
        return true;
    }
}
