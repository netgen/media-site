<?php

declare(strict_types=1);

namespace App\EventListener\Ibexa\Bookmarks;

use App\Doctrine\Repository\BookmarkRepository;
use Ibexa\Contracts\Core\Repository\Events\Content\UpdateContentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UpdateTitleListener implements EventSubscriberInterface
{
    public function __construct(private BookmarkRepository $bookmarkRepository) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UpdateContentEvent::class => 'onUpdateContent',
        ];
    }

    public function onUpdateContent(UpdateContentEvent $event): void
    {
        $content = $event->getContent();

        $this->bookmarkRepository->updateBookmarkNames($content->id, (string) $content->getName());
    }
}
