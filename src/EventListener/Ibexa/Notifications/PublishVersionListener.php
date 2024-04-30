<?php

declare(strict_types=1);

namespace App\EventListener\Ibexa\Notifications;

use Ibexa\Contracts\Core\Repository\Events\Content\PublishVersionEvent;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Netgen\Notifications\Category\NotificationCategory;
use Netgen\Notifications\Category\Resolver\CategoryResolverInterface;
use Netgen\Notifications\Category\TwigTemplate;
use Netgen\Notifications\Context;
use Netgen\Notifications\Manager\NotificationManagerInterface;
use Netgen\Notifications\NotificationData;
use Netgen\Notifications\Registry\NotificationCategoryRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use function sprintf;

final class PublishVersionListener implements EventSubscriberInterface
{
    /**
     * @param \Netgen\Notifications\Category\Resolver\CategoryResolverInterface<object> $categoryResolver
     */
    public function __construct(
        private CategoryResolverInterface $categoryResolver,
        private NotificationCategoryRegistry $categoryRegistry,
        private NotificationManagerInterface $notificationManager,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            PublishVersionEvent::class => 'onPublishVersion',
        ];
    }

    public function onPublishVersion(PublishVersionEvent $event): void
    {
        $mainLocation = $event->getContent()->contentInfo->getMainLocation();
        if ($mainLocation === null) {
            return;
        }

        $locationCategory = $this->getLocationCategory($mainLocation);
        if ($locationCategory === null) {
            return;
        }

        if ($event->getContent()->versionInfo->versionNo === 1) {
            $this->sendNewContentNotification($event->getContent(), $mainLocation, $locationCategory);

            return;
        }

        $this->sendUpdatedContentNotification($event->getContent(), $mainLocation, $locationCategory);
    }

    private function getLocationCategory(Location $location): ?NotificationCategory
    {
        $categoryIdentifier = $this->categoryResolver->resolveCategory($location);

        if ($categoryIdentifier !== null) {
            $category = $this->categoryRegistry->getCategory($categoryIdentifier);

            if ($category->subscription->enabled) {
                return $category;
            }
        }

        return null;
    }

    private function sendNewContentNotification(Content $content, Location $location, NotificationCategory $category): void
    {
        $this->notificationManager->sendNotification(
            $category->identifier,
            [],
            new NotificationData(
                sprintf('New content "%s" has been published', $content->getName()),
                new TwigTemplate(
                    'backoffice/notifications/new_content.html.twig',
                    [
                        'content_id' => $content->id,
                        'content_name' => $content->getName(),
                    ],
                ),
            ),
            new Context(
                [
                    'contentId' => $content->id,
                    'locationId' => $location->id,
                ],
            ),
        );
    }

    private function sendUpdatedContentNotification(Content $content, Location $location, NotificationCategory $category): void
    {
        $this->notificationManager->sendNotification(
            $category->identifier,
            [],
            new NotificationData(
                sprintf('Content "%s" has been updated', $content->getName()),
                new TwigTemplate(
                    'backoffice/notifications/updated_content.html.twig',
                    [
                        'content_id' => $content->id,
                        'content_name' => $content->getName(),
                    ],
                ),
            ),
            new Context(
                [
                    'contentId' => $content->id,
                    'locationId' => $location->id,
                ],
            ),
        );
    }
}
