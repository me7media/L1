<?php

namespace App\Messages;


use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\Part\TextPart;

class EmailMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EsmtpTransport
     */
    private $transport;

    /**
     * EmailMessageHandler constructor.
     * @param EsmtpTransport $transport
     */
    public function __construct(EsmtpTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param EmailMessage $email
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(EmailMessage $email)
    {
        $headers = (new Headers())
            ->addMailboxListHeader('From', [$email->getFrom()])
            ->addMailboxListHeader('To', [$email->getTo()])
            ->addTextHeader('Subject', $email->getTitle());
        $body = new TextPart($email->getBody(), 'text/html');
        $message = new Message($headers, $body);

        $this->transport->send($message);
    }
}