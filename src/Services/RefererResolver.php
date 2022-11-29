<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RefererResolver
{
    private RequestStack $requestStack;
    private RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function getReferer(?int $refererLocationId = null): string
    {
        if ($refererLocationId !== null) {
            return $this->router->generate(
                'ibexa.url.alias',
                [
                    'locationId' => $refererLocationId,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new RuntimeException('Missing Request');
        }

        if ($request->headers->has('referer')) {
            return $request->headers->get('referer');
        }

        return $request->getPathInfo();
    }
}
