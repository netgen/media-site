<?php

declare(strict_types=1);

namespace App\Controller;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentRenderer;
use Symfony\Component\HttpFoundation\Response;

class ModalViewController extends Controller
{
    private ContentRenderer $contentRenderer;

    public function __construct(ContentRenderer $contentRenderer)
    {
        $this->contentRenderer = $contentRenderer;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Netgen\IbexaSiteApi\API\Exceptions\TranslationNotMatchedException
     */
    public function viewModal(int $contentId): Response
    {
        return new Response(
            $this->contentRenderer->renderContent(
                $this->getSite()->getLoadService()->loadContent($contentId),
                'modal',
            )
        );
    }
}
