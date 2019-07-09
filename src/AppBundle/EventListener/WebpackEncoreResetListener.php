<?php

declare(strict_types=1);

namespace App\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;

final class WebpackEncoreResetListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface
     */
    private $entryPointLookupCollection;

    public function __construct(EntrypointLookupCollectionInterface $entryPointLookupCollection)
    {
        $this->entryPointLookupCollection = $entryPointLookupCollection;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $this->entryPointLookupCollection->getEntrypointLookup('app')->reset();
    }
}
