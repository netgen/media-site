<?php

declare(strict_types=1);

namespace App\Backoffice\Form\Bookmarks;

use Generator;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;

use function in_array;
use function ksort;

final class ContentType
{
    public function __construct(
        private ContentTypeService $contentTypeService,
        private ConfigResolverInterface $configResolver,
    ) {}

    /**
     * @return iterable<string, int>
     */
    public function __invoke(): iterable
    {
        $allowedContentTypes = $this->configResolver->getParameter('bookmarks.content_types', 'ngsite');

        /** @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType[] $contentTypes */
        $contentTypes = (function (): Generator {
            $contentTypeGroups = $this->contentTypeService->loadContentTypeGroups();
            foreach ($contentTypeGroups as $contentTypeGroup) {
                yield from $this->contentTypeService->loadContentTypes($contentTypeGroup);
            }
        })();

        $typeOptions = [];

        foreach ($contentTypes as $contentType) {
            if (in_array($contentType->identifier, $allowedContentTypes, true)) {
                $contentTypeName = $contentType->getName();
                $typeOptions[$contentTypeName] = $contentType->id;
            }
        }

        ksort($typeOptions);

        return $typeOptions;
    }
}
