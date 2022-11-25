<?php

declare(strict_types=1);

namespace App\InformationCollection\Action;

use App\Services\NewsletterService;
use Netgen\InformationCollection\API\Action\ActionInterface;
use Netgen\InformationCollection\API\Value\Event\InformationCollected;
use Symfony\Component\Mailer\MailerInterface;

final class NewsletterAction implements ActionInterface
{
    private MailerInterface $mailer;
    private NewsletterService $newsletterService;

    public function __construct(
        MailerInterface $mailer,
        NewsletterService $newsletterService
    ) {
        $this->mailer = $mailer;
        $this->newsletterService = $newsletterService;
    }

    public function act(InformationCollected $event): void
    {
        $this->newsletterService->subscribeToNewsletters(
            $event->getContent(),
            $event->getInformationCollectionStruct()->getCollectedFields(),
        );
    }
}
