<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Index extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('index.html.twig');
    }
}
