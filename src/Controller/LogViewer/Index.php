<?php

declare(strict_types=1);

namespace App\Controller\LogViewer;

use App\Form\LogViewer\FilterType;
use App\Repository\LogRepository;
use App\Service\Export\LogExport;
use Ibexa\Bundle\Core\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use function abs;

final class Index extends Controller
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private LogRepository $logRepository,
        private LogExport $logExport,
    ) {}

    public function __invoke(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted('ibexa:ngsite_logger:view_logs')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(FilterType::class);

        $form->handleRequest($request);

        $logs = $this->logRepository->search(
            $form->get('message')->getData(),
            $form->get('severity')->getData(),
            $form->get('module')->getData(),
            $form->get('email')->getData(),
            $form->get('dateFrom')->getData(),
            $form->get('dateTo')->getData(),
        );

        /** @var ClickableInterface $exportButton */
        $exportButton = $form->get('export');
        if ($exportButton->isClicked()) {
            return $this->logExport->exportLogs($logs);
        }

        $logs = new Pagerfanta(new ArrayAdapter($logs));
        $logs->setNormalizeOutOfRangePages(true);
        $logs->setMaxPerPage($this->getConfigResolver()->getParameter('pager_limit', 'ngsite'));

        $page = abs($request->query->getInt('page', 1));
        $logs->setCurrentPage($page > 0 ? $page : 1);

        return $this->render(
            '@ibexadesign/log_viewer/index.html.twig',
            [
                'form' => $form->createView(),
                'logs' => $logs,
            ],
        );
    }
}
