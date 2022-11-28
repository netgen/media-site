<?php

namespace App\Controller;

use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModalViewController extends Controller
{
    /**
     * @todo use form content ID, render modal view, check ctype, rename serveModalForm, generic for modal view?
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Netgen\IbexaSiteApi\API\Exceptions\TranslationNotMatchedException
     */
    public function viewModal(Request $request, int $formLocationId, ?int $refererLocationId = null): Response
    {
        $location = $this->getSite()->getLoadService()->loadLocation($formLocationId);

        $response = new Response();
        $response->setSharedMaxAge(0);
        $response->setPrivate();

        // todo render modal view
        return $this->render(
            '@ibexadesign/info_collection/modal.html.twig',
            [
                'content' => $location->content,
                'location' => $location,
                'view_type' => 'embed',
                'referer' => $this->getReferer($refererLocationId),
            ]
        );
    }
}
