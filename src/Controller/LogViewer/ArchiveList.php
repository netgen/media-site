<?php

declare(strict_types=1);

namespace App\Controller\LogViewer;

use Ibexa\Bundle\Core\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ArchiveList extends Controller
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private readonly string $logsDir,
    ) {}

    public function __invoke(): Response
    {
        if (!$this->authorizationChecker->isGranted('ibexa:ngsite_logger:view_logs')) {
            throw $this->createAccessDeniedException();
        }

        $finder = new Finder();
        $finder->files()->in($this->logsDir);

        return $this->render(
            '@ibexadesign/log_viewer/archive_list.html.twig',
            [
                'files' => $finder,
            ],
        );
    }
}
