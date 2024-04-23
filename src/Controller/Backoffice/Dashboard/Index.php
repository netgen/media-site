<?php

declare(strict_types=1);

namespace App\Controller\Backoffice\Dashboard;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class Index extends Controller
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        return $this->render('@app/backoffice/dashboard/index.html.twig');
    }
}
