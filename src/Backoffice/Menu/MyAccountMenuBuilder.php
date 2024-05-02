<?php

declare(strict_types=1);

namespace App\Backoffice\Menu;

use App\Attribute\AsMenuBuilder;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

#[AsMenuBuilder('createMenu', 'backoffice.my_account_menu')]
final class MyAccountMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
    ) {}

    public function createMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('personal_details', ['route' => 'backoffice_my_account_personal_details'])
            ->setLabel('menu.main_menu.personal_details')
            ->setExtra('translation_domain', 'backoffice');

        $menu
            ->addChild('user_credentials', ['route' => 'backoffice_my_account_user_credentials'])
            ->setLabel('menu.main_menu.user_credentials')
            ->setExtra('translation_domain', 'backoffice');

        return $menu;
    }
}
