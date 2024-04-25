<?php

declare(strict_types=1);

namespace App\Doctrine\EventListener;

use App\Entity\Bookmark;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Netgen\IbexaSiteApi\API\LoadService;

#[AsEntityListener(event: 'postLoad', entity: Bookmark::class)]
#[AsEntityListener(event: 'postPersist', entity: Bookmark::class)]
final class BookmarkPostLoadListener
{
    public function __construct(private LoadService $loadService) {}

    public function postLoad(Bookmark $bookmark, PostLoadEventArgs $args): void
    {
        $this->enrichEntity($bookmark);
    }

    public function postPersist(Bookmark $bookmark, PostPersistEventArgs $args): void
    {
        $this->enrichEntity($bookmark);
    }

    private function enrichEntity(Bookmark $bookmark): void
    {
        try {
            $bookmark->setLocation(
                $this->loadService->loadLocation($bookmark->getLocationId()),
            );
        } catch (NotFoundException) {
            // Do nothing
        }
    }
}
