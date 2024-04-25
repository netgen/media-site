<?php

declare(strict_types=1);

namespace App\Controller\API\Bookmarks;

use App\Repository\BookmarkRepository;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Netgen\IbexaSiteApi\API\Values\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Delete extends Controller
{
    public function __construct(
        private BookmarkRepository $repository,
    ) {}

    public function __invoke(Location $location, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();

        $bookmark = $this->repository->findOneBy(
            [
                'userId' => $sfUser->getAPIUser()->id,
                'locationId' => $location->id,
            ],
        ) ?? throw $this->createNotFoundException();

        $this->repository->remove($bookmark, true);

        return new Response();
    }
}
