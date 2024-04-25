<?php

declare(strict_types=1);

namespace App\Twig;

use App\Doctrine\Entity\Bookmark;
use App\Doctrine\Repository\BookmarkRepository;
use Netgen\IbexaSiteApi\API\Values\Location;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;

final class BookmarkRuntime implements RuntimeExtensionInterface
{
    public function __construct(private BookmarkRepository $bookmarkRepository, private Security $security) {}

    public function getBookmark(Location $location): ?Bookmark
    {
        /** @var \Ibexa\Core\MVC\Symfony\Security\User $symfonyUser */
        $symfonyUser = $this->security->getUser();

        return $this->bookmarkRepository->findOneBy(
            [
                'userId' => $symfonyUser->getAPIUser()->id ?? 0,
                'locationId' => $location->id,
            ],
        );
    }
}
