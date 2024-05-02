<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\MyAccount;

use App\Backoffice\Doctrine\Repository\SecurityTokenRepository;
use DateTimeImmutable;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ChangeEmailConfirmation extends Controller
{
    public function __construct(
        private readonly Repository $repository,
        private readonly UserService $userService,
        private readonly SecurityTokenRepository $tokenRepository,
    ) {}

    public function __invoke(Request $request, string $token): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();
        $user = $sfUser->getAPIUser();

        $changeEmailToken = $this->tokenRepository->findOneBy(
            [
                'userId' => $user->id,
                'token' => $token,
            ],
        );

        if ($changeEmailToken === null) {
            throw new BadRequestHttpException('Request for e-mail change with given token does not exist.');
        }

        if ($changeEmailToken->isUsed()) {
            $this->addFlash('notice', 'my_account.change_email.already_activated');

            return $this->redirectToRoute('backoffice_my_account_user_credentials');
        }

        if ($changeEmailToken->getExpiryDate() < new DateTimeImmutable()) {
            $this->addFlash('notice', 'my_account.change_email.token_expired');

            return $this->redirectToRoute('backoffice_my_account_user_credentials');
        }

        $userUpdateStruct = $this->userService->newUserUpdateStruct();
        $userUpdateStruct->email = $changeEmailToken->getData();
        $this->repository->sudo(
            fn (): User => $this->userService->updateUser($user, $userUpdateStruct),
        );

        $changeEmailToken->setIsUsed(true);
        $this->tokenRepository->save($changeEmailToken, true);

        $this->addFlash('success', 'my_account.change_email.successfully_changed');

        return $this->redirectToRoute('backoffice_my_account_user_credentials');
    }
}
