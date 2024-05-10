<?php

declare(strict_types=1);

namespace App\Messenger\MessageHandler\Notifications;

use App\Enums\Log\Module;
use App\Logger\ActionLoggerInterface;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Repository;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Notifications\Message\CreateNotification;
use Netgen\Notifications\MessageHandler\CreateNotificationHandler as OriginalCreateNotificationHandler;
use Netgen\Notifications\Repository\UserRepository;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

use function sprintf;

// Does not have #[AsMessageHandler] attribute by design
// since this decorates the original CreateNotificationHandler handler
final class CreateNotificationHandler
{
    public function __construct(
        private ActionLoggerInterface $logger,
        private UserRepository $userRepository,
        private LoadService $loadService,
        private Repository $repository,
        private OriginalCreateNotificationHandler $innerHandler,
    ) {}

    public function __invoke(CreateNotification $message): void
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

        $notificationUser = $this->userRepository->findOneBy(['uuid' => $message->userUuid]) ??
            throw new UnrecoverableMessageHandlingException(
                sprintf(
                    'User with %s UUID does not exist',
                    $message->userUuid,
                ),
            );

        try {
            $user = $this->repository->sudo(
                fn () => $this->loadService->loadContent((int) $notificationUser->getExternalUserId()),
            );
        } catch (NotFoundException $e) {
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }

        $this->logger->info(
            Module::Notifications,
            sprintf(
                'Notification with "%s" title in "%s" category was sent to user %s (# %d)',
                $message->data->title ?? '',
                $message->category,
                $user->name,
                $user->id,
            ),
        );
    }
}
