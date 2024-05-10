<?php

declare(strict_types=1);

namespace App\EventListener\Security;

use App\Enums\Log\Module;
use App\Logger\ActionLoggerInterface;
use DateTimeImmutable;
use Ibexa\Core\MVC\Symfony\Security\UserInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

use function sprintf;

final class UserVisitListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ActionLoggerInterface $actionLogger,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'saveUserVisit',
        ];
    }

    public function saveUserVisit(InteractiveLoginEvent $event): void
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            $this->logger->critical(
                sprintf(
                    'Could not save user login date, user is not instance of %s, please check execution priority of %s',
                    UserInterface::class,
                    self::class,
                ),
            );

            return;
        }

        $date = new DateTimeImmutable();
        $this->actionLogger->info(Module::Login, sprintf(
            'User with email "%s" logged in on "%s" ',
            $user->getAPIUser()->email,
            $date->format('Y-m-d H:i:s'),
        ), );
    }
}
