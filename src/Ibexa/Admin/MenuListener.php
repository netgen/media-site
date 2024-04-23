<?php

declare(strict_types=1);

namespace App\Ibexa\Admin;

use Ibexa\AdminUi\Menu\Event\ConfigureMenuEvent;
use Ibexa\AdminUi\Menu\MainMenuBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MenuListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMenuEvent::MAIN_MENU => ['onMenuConfigure', 0],
        ];
    }

    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        if (!isset($menu[MainMenuBuilder::ITEM_ADMIN])) {
            return;
        }

        $menu[MainMenuBuilder::ITEM_ADMIN]->addChild(
            'import_export',
            [
                'label' => 'Import/Export',
                'route' => 'netgen_import_export.route.admin.overview',
            ],
        );
    }
}
