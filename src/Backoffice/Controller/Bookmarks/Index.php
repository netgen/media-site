<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\Bookmarks;

use App\Backoffice\Doctrine\Repository\BookmarkRepository;
use App\Backoffice\Form\Bookmarks\ContentType;
use App\Backoffice\Form\Filter\FilterType;
use App\Backoffice\Pagerfanta\BookmarksAdapter;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function abs;

final class Index extends Controller
{
    public function __construct(
        private BookmarkRepository $repository,
        private ContentTypeService $contentTypeService,
        private ConfigResolverInterface $configResolver,
    ) {}

    /**
     * Displays the bookmarks page of the backoffice.
     */
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $filterForm = $this->createForm(
            FilterType::class,
            null,
            [
                'translation_prefix' => 'bookmarks',
                'show_date' => true,
                'show_search' => true,
                'selections' => [
                    'type' => [
                        'options' => new ContentType($this->contentTypeService, $this->configResolver),
                    ],
                ],
            ],
        );

        $filterForm->handleRequest($request);

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();

        $adapter = new BookmarksAdapter(
            $this->repository,
            $sfUser->getAPIUser()->id,
            $filterForm->get('selections')->get('type')->getData(),
            $filterForm->get('searchText')->getData(),
            $filterForm->get('date')->get('dateFrom')->getData(),
            $filterForm->get('date')->get('dateTo')->getData(),
        );

        $bookmarks = new Pagerfanta($adapter);
        $bookmarks->setNormalizeOutOfRangePages(true);
        $bookmarks->setMaxPerPage($this->getConfigResolver()->getParameter('pager_limit', 'ngsite'));

        $page = abs($request->query->getInt('page', 1));
        $bookmarks->setCurrentPage($page > 0 ? $page : 1);

        return $this->render(
            'backoffice/bookmarks/index.html.twig',
            [
                'filter_form' => $filterForm->createView(),
                'bookmarks' => $bookmarks,
            ],
        );
    }
}
