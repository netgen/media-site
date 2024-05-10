<?php

declare(strict_types=1);

namespace App\Messenger\MessageHandler\Notifications;

use App\Enums\Log\Module;
use App\Logger\ActionLoggerInterface;
use Netgen\Notifications\Message\SendNotification;
use Netgen\Notifications\MessageHandler\SendNotificationHandler as OriginalSendNotificationHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

use function sprintf;

// Does not have #[AsMessageHandler] attribute by design
// since this decorates the original SendNotificationHandler handler
final class SendNotificationHandler
{
    public function __construct(
        private ActionLoggerInterface $logger,
        private OriginalSendNotificationHandler $innerHandler,
    ) {}

    public function __invoke(SendNotification $message): void
    {
        try {
            ($this->innerHandler)($message);
        } catch (UnrecoverableMessageHandlingException $e) {
            $this->logger->info(
                Module::Notifications,
                sprintf(
                    'Notification with "%s" title was not sent: %s',
                    $message->data->title ?? '',
                    $e->getMessage(),
                ),
            );

            throw $e;
        }
    }
}
