<?php

declare(strict_types=1);

namespace App\EventListener\Security;

use App\Security\UserRoleResolver;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Core\MVC\Symfony\Security\InteractiveLoginToken;
use Ibexa\Core\MVC\Symfony\Security\UserInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

use function array_filter;
use function array_merge;
use function array_unique;
use function get_class;
use function method_exists;
use function sprintf;

final class InteractiveLoginListener implements EventSubscriberInterface
{
    public function __construct(
        private UserRoleResolver $userRoleResolver,
        private TokenStorageInterface $tokenStorage,
        private LoggerInterface $logger = new NullLogger(),
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            $this->logger->critical(
                sprintf(
                    'Could not set Symfony roles based on Ibexa user groups, user is not instance of %s, please check execution priority of %s',
                    UserInterface::class,
                    self::class,
                ),
            );

            return;
        }

        $firewallName = method_exists($token, 'getFirewallName') ? $token->getFirewallName() : __CLASS__;

        $interactiveToken = new InteractiveLoginToken(
            $user,
            get_class($token),
            $token->getCredentials(),
            $firewallName,
            $this->getRoles($user->getAPIUser(), $token),
        );

        $interactiveToken->setAttributes($token->getAttributes());

        $this->tokenStorage->setToken($interactiveToken);
    }

    /**
     * @return string[]
     */
    private function getRoles(User $user, TokenInterface $token): array
    {
        $existingRoles = $token->getRoleNames();
        $newRoles = $this->userRoleResolver->resolveRoles($user);

        return array_filter(array_unique(array_merge($existingRoles, $newRoles)));
    }
}
