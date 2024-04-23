<?php

declare(strict_types=1);

namespace App\Controller\Export;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Download extends AbstractController
{
    private const MIGRATIONS_DIRECTORY = 'var/cache/migrations/';

    public function __invoke(Request $request, string $file): Response
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $filePath = $projectRoot . '/' . $this::MIGRATIONS_DIRECTORY . $file;
        $response = new BinaryFileResponse($filePath);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file);

        return $response;
    }
}
