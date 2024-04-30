<?php

declare(strict_types=1);

namespace App\Backoffice\Menu;

use App\Attribute\AsMenuBuilder;
use Ibexa\Contracts\Core\Repository\Repository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Netgen\Conversations\Participant\ParticipantProviderInterface;
use Netgen\Conversations\Repository\ConversationRepository;
use Netgen\IbexaSiteApi\API\LoadService;
use Netgen\Notifications\Repository\NotificationRepository;
use Netgen\Notifications\User\UserProviderInterface;
use Symfony\Component\Security\Core\Security;

#[AsMenuBuilder('createMenu', 'app.backoffice.main_menu')]
final class MainMenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private ConversationRepository $conversationRepository,
        private ParticipantProviderInterface $participantProvider,
        private NotificationRepository $notificationRepository,
        private UserProviderInterface $userProvider,
        private LoadService $loadService,
        private Repository $repository,
        private Security $security,
    ) {}

    public function createMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('dashboard', ['route' => 'backoffice_dashboard_index'])
            ->setLabel('menu.main_menu.dashboard')
            ->setExtra('translation_domain', 'backoffice')
            ->setExtra('icon_class', 'icon-dashboard');

        $menu
            ->addChild('bookmarks', ['route' => 'backoffice_bookmarks_index'])
            ->setLabel('menu.main_menu.bookmarks')
            ->setExtra('translation_domain', 'backoffice')
            ->setExtra('icon_class', 'icon-bookmarks');

        $menu
            ->addChild('conversations', ['route' => 'ngconversations_app'])
            ->setLabel('menu.main_menu.conversations')
            ->setExtra('translation_domain', 'backoffice')
            ->setExtra('icon_class', 'icon-conversations')
            ->setExtra(
                'icon_count',
                $this->conversationRepository->findUnreadParticipantConversationsCount(
                    $this->participantProvider->provideParticipant(),
                ),
            );

        $menu
            ->addChild('notifications')
            ->setLabel('menu.main_menu.notifications')
            ->setExtra('translation_domain', 'backoffice')
            ->setExtra('icon_class', 'icon-notifications')
            ->setExtra(
                'icon_count',
                $this->notificationRepository->getUserNotificationsCount(
                    $this->userProvider->provideUser(),
                    false,
                ),
            );

        /** @var \Knp\Menu\ItemInterface $notificationsMenu */
        $notificationsMenu = $menu->getChild('notifications');

        $notificationsMenu
            ->addChild('notifications_inbox', ['route' => 'ngnotifications_admin_inbox'])
            ->setLabel('menu.main_menu.notifications.inbox')
            ->setExtra('translation_domain', 'backoffice');

        $notificationsMenu
            ->addChild('notifications_archive', ['route' => 'ngnotifications_admin_archive'])
            ->setLabel('menu.main_menu.notifications.archive')
            ->setExtra('translation_domain', 'backoffice');

        $notificationsMenu
            ->addChild('notifications_subscriptions', ['route' => 'ngnotifications_admin_subscriptions'])
            ->setLabel('menu.main_menu.notifications.subscriptions')
            ->setExtra('translation_domain', 'backoffice');

        return $menu;
    }
}
