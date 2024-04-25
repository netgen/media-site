<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\Bookmarks;

use App\Backoffice\Doctrine\Entity\Bookmark;
use App\Backoffice\Doctrine\Repository\BookmarkRepository;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Delete extends Controller
{
    public function __construct(
        private BookmarkRepository $repository,
    ) {}

    public function __invoke(Bookmark $bookmark, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->remove($bookmark, true);

            return $this->redirectToRoute('backoffice_bookmarks_index');
        }

        return $this->render(
            'backoffice/bookmarks/delete.html.twig',
            [
                'form' => $form->createView(),
                'bookmark' => $bookmark,
            ],
        );
    }
}
