<?php

declare(strict_types=1);

namespace App\Controller\LogViewer;

use Ibexa\Bundle\Core\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class Download extends Controller
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private readonly string $logsDir,
    ) {}

    public function __invoke(string $fileName): BinaryFileResponse
    {
        if (!$this->authorizationChecker->isGranted('ibexa:ngsite_logger:view_logs')) {
            throw $this->createAccessDeniedException();
        }

        $response = new BinaryFileResponse($this->logsDir . '/' . $fileName);
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache');
        $response->headers->addCacheControlDirective('no-store');
        $response->headers->addCacheControlDirective('must-revalidate');

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName);

        return $response;
    }
}
