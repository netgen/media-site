<?php

declare(strict_types=1);

namespace App\Controller;

use App\InformationCollection\Handler\Handler;
use App\Services\RefererResolver;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentRenderer;
use Netgen\Bundle\IbexaSiteApiBundle\View\ContentView;
use Netgen\IbexaSiteApi\API\Values\Location;
use Netgen\InformationCollection\API\Events;
use Netgen\InformationCollection\API\Service\CaptchaService;
use Netgen\InformationCollection\API\Value\Event\InformationCollected;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class InfoCollectionController extends Controller
{
    private RequestStack $requestStack;
    private CaptchaService $captchaService;
    private Handler $handler;
    private EventDispatcherInterface $eventDispatcher;
    private TranslatorInterface $translator;
    private ContentRenderer $contentRenderer;
    private RefererResolver $refererResolver;
    private LoggerInterface $logger;

    public function __construct(
        RequestStack $requestStack,
        CaptchaService $captchaService,
        Handler $handler,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
        ContentRenderer $contentRenderer,
        RefererResolver $refererResolver,
        LoggerInterface $logger
    ) {
        $this->requestStack = $requestStack;
        $this->captchaService = $captchaService;
        $this->handler = $handler;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->contentRenderer = $contentRenderer;
        $this->refererResolver = $refererResolver;
        $this->logger = $logger;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Netgen\IbexaSiteApi\API\Exceptions\TranslationNotMatchedException
     *
     * @todo remove in favor of generic implementation
     */
    public function viewModal(int $formContentId, ?int $refererLocationId = null): Response
    {
        $response = new Response(
            $this->contentRenderer->renderContent(
                $this->getSite()->getLoadService()->loadContent($formContentId),
                'modal',
                [
                    'referer' => $this->refererResolver->getReferer($refererLocationId),
                ]
            )
        );

        $response->setSharedMaxAge(0);
        $response->setPrivate();

        return $response;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Netgen\IbexaSiteApi\API\Exceptions\TranslationNotMatchedException
     *
     * @todo post
     */
    public function ajaxSubmit(int $formContentId): Response
    {
        return new Response(
            $this->contentRenderer->renderContent(
                $this->getSite()->getLoadService()->loadContent($formContentId),
                'payload'
            )
        );
    }

    /**
     * @todo Describe
     */
    public function proxyFormHandler(ContentView $view): ContentView
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new RuntimeException('Missing Request');
        }

        $location = $view->getSiteLocation();

        if ($location === null) {
            throw new RuntimeException('Missing Location context');
        }

        $refererLocationId = null;

        if ($view->hasParameter('refererLocationId')) {
            $refererLocationId = $view->getParameter('refererLocationId');
        }

        $view->addParameters(
            array_merge(
                $this->collectInformation($location, $request),
                [
                    'content' => $location->content,
                    'location' => $location,
                    'view' => $view,
                    'referer' => $this->refererResolver->getReferer($refererLocationId),
                ],
            ),
        );

        $view->setCacheEnabled(false);

        return $view;
    }

    private function collectInformation(Location $location, Request $request): array
    {
        $form = $this->handler->getForm(
            $location->innerLocation->getContent(),
            $location->innerLocation
        );

        $isCollected = false;
        $captcha = $this->captchaService->getCaptcha($location->innerLocation);

        $form->handleRequest($request);
        $formSubmitted = $form->isSubmitted();
        $validCaptcha = $captcha->isValid($request);

        /** @noinspection NotOptimalIfConditionsInspection */
        if ($formSubmitted && $form->isValid() && $validCaptcha) {
            if ($this->isHoneypotEmpty($form)) {
                $event = new InformationCollected($form->getData(), []);

                $this->eventDispatcher->dispatch($event, Events::INFORMATION_COLLECTED);
            }

            $isCollected = true;
        }

        if ($formSubmitted && !$form->isValid()) {
            $this->logger->critical((string) $form->getErrors(true, false));
        }

        if ($formSubmitted && !$validCaptcha) {
            $message = $this->translator->trans('ngsite.collected_info.error.captcha_failed');

            $this->logger->critical($message);

            $form->addError(new FormError($message));
        }

        return [
            'is_collected' => $isCollected,
            'form' => $form->createView(),
        ];
    }

    private function isHoneypotEmpty(FormInterface $form): bool
    {
        if (!$form->has('sender_middle_name')) {
            return true;
        }

        $honeypot = $form->get('sender_middle_name');

        return $honeypot->getData() === '';
    }
}
