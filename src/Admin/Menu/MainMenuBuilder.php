<?php

declare(strict_types=1);

namespace App\Admin\Menu;

use App\Attribute\AsMenuBuilder;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Netgen\Conversations\Participant\ParticipantProviderInterface;
use Netgen\Conversations\Repository\ConversationRepository;

#[AsMenuBuilder('createMenu', 'app.admin.main_menu')]
final class MainMenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private ConversationRepository $conversationRepository,
        private ParticipantProviderInterface $participantProvider,
    ) {}

    public function createMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('conversations', ['route' => 'ngconversations_app'])
            ->setLabel('menu.main_menu.conversations')
            ->setExtra('translation_domain', 'admin')
            ->setExtra('icon_class', 'icon-conversations')
            ->setExtra(
                'icon_count',
                $this->conversationRepository->findUnreadParticipantConversationsCount(
                    $this->participantProvider->provideParticipant(),
                ),
            );

        return $menu;
    }
}
