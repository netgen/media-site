<?php

declare(strict_types=1);

namespace App\InformationCollection\Action;

use Netgen\InformationCollection\API\Action\ActionInterface;
use Netgen\InformationCollection\API\Exception\ActionFailedException;
use Netgen\InformationCollection\API\Exception\EmailNotSentException;
use Netgen\InformationCollection\API\Factory\EmailContentFactoryInterface;
use Netgen\InformationCollection\API\Value\DataTransfer\EmailContent;
use Netgen\InformationCollection\API\Value\Event\InformationCollected;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class EmailAction implements ActionInterface
{
    private MailerInterface $mailer;
    private EmailContentFactoryInterface $factory;

    public function __construct(
        MailerInterface $mailer,
        EmailContentFactoryInterface $factory
    ) {
        $this->mailer = $mailer;
        $this->factory = $factory;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function act(InformationCollected $event): void
    {
        $emailContent = $this->factory->build($event);
        $message = $this->convertToEmailMessage($emailContent);

        try {
            $this->mailer->send($message);
        } catch (EmailNotSentException $e) {
            $this->throwException($e);
        }
    }

    private function convertToEmailMessage(EmailContent $emailContent): Email
    {
        $email = new Email();

        foreach ($emailContent->getSender() as $sender) {
            $email->addFrom(Address::create($sender));
        }

        foreach ($emailContent->getRecipients() as $recipient) {
            $email->addTo(Address::create($recipient));
        }

        $email->subject($emailContent->getSubject());
        $email->html($emailContent->getBody());

        foreach ($emailContent->getAttachments() as $attachment) {
            $email->attachFromPath(
                $attachment->inputUri,
                $attachment->fileName,
                $attachment->mimeType,
            );
        }

        return $email;
    }

    private function throwException(EmailNotSentException $exception): void
    {
        throw new ActionFailedException(__CLASS__, $exception->getMessage());
    }
}
