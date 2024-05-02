<?php

declare(strict_types=1);

namespace App\Controller\PageLayout;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserMenu extends Controller
{
    public function __invoke(Request $request): Response
    {
        $response = new Response();

        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache');
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->addCacheControlDirective('must-revalidate');

        return $this->render(
            '@ibexadesign/pagelayout/header/login.html.twig',
            [],
            $response,
        );
    }
}
