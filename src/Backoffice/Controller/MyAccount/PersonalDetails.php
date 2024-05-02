<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\MyAccount;

use App\Backoffice\Form\MyAccount\PersonalDetailsType;
use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Core\Base\Exceptions\ContentFieldValidationException;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function is_array;

final class PersonalDetails extends Controller
{
    public function __construct(
        private readonly Repository $repository,
        private readonly ContentService $contentService,
        private readonly UserService $userService,
    ) {}

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();
        $user = $sfUser->getAPIUser();

        $form = $this->createForm(
            PersonalDetailsType::class,
            $user,
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Backoffice\Form\MyAccount\PersonalDetailsData $formData */
            $formData = $form->getData();

            $updateStruct = $this->userService->newUserUpdateStruct();

            $updateStruct->contentUpdateStruct = $this->contentService->newContentUpdateStruct();

            if ($formData->removeImage) {
                $updateStruct->contentUpdateStruct->setField('image', null);
            } elseif ($formData->image !== null) {
                $updateStruct->contentUpdateStruct->setField('image', $formData->image);
            }

            $updateStruct->contentUpdateStruct->setField('first_name', $formData->firstName);
            $updateStruct->contentUpdateStruct->setField('last_name', $formData->lastName);

            $errors = [];

            try {
                $this->repository->sudo(
                    fn (): User => $this->userService->updateUser(
                        $user,
                        $updateStruct,
                    ),
                );
            } catch (ContentFieldValidationException $e) {
                foreach ($e->getFieldErrors() as $validationErrors) {
                    foreach ($validationErrors as $validationError) {
                        if (is_array($validationError)) {
                            foreach ($validationError as $item) {
                                $errors[] = $item->getTranslatableMessage();
                            }
                        } else {
                            $errors[] = $validationError->getTranslatableMessage();
                        }
                    }
                }

                return $this->render(
                    'backoffice/my_account/personal_details.html.twig',
                    [
                        'form' => $form->createView(),
                        'errors' => $errors,
                    ],
                );
            }

            $this->addFlash(
                'success',
                'my_account.user_successfully_updated',
            );

            return $this->redirectToRoute('backoffice_my_account_personal_details');
        }

        return $this->render(
            'backoffice/my_account/personal_details.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}
