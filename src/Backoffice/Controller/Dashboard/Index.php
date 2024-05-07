<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\Dashboard;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

final class Index extends Controller
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        return $this->render('backoffice/dashboard/index.html.twig');
    }
}