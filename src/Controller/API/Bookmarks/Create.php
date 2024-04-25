<?php

declare(strict_types=1);

namespace App\Controller\API\Bookmarks;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Netgen\IbexaSiteApi\API\Values\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function in_array;

final class Create extends Controller
{
    public function __construct(
        private BookmarkRepository $repository,
        private ConfigResolverInterface $configResolver,
    ) {}

    public function __invoke(Location $location, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $allowedContentTypes = $this->configResolver->getParameter('bookmarks.content_types', 'ngsite');
        if (!in_array($location->contentInfo->contentTypeIdentifier, $allowedContentTypes, true)) {
            throw new BadRequestHttpException('This location cannot be bookmarked!');
        }

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();

        $existingBookmark = $this->repository->findOneBy(
            [
                'userId' => $sfUser->getAPIUser()->id,
                'locationId' => $location->id,
            ],
        );

        if ($existingBookmark instanceof Bookmark) {
            throw new BadRequestHttpException('Bookmark already exists!');
        }

        $bookmark = Bookmark::create(
            $sfUser->getAPIUser()->id,
            $location->contentInfo->id,
            $location->id,
            $location->contentInfo->contentTypeId,
            $location->contentInfo->name ?? '',
        );

        $this->repository->save($bookmark, true);

        return new Response();
    }
}
