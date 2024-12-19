<?php

declare(strict_types=1);

namespace App\Controller;

use Netgen\Bundle\SiteBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class ViewPayload extends Controller
{
    public function __invoke(int $contentId): Response
    {
        return new Response(
            $this->getContentRenderer()->renderContent(
                $this->getLoadService()->loadContent($contentId),
                'payload',
            ),
        );
    }
}
