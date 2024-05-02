<?php

declare(strict_types=1);

namespace App\Backoffice\Controller\MyAccount;

use App\Backoffice\Doctrine\Entity\SecurityToken;
use App\Backoffice\Doctrine\Repository\SecurityTokenRepository;
use App\Backoffice\Enums\SecurityTokenType;
use App\Backoffice\Form\MyAccount\ChangeEmailType;
use App\Backoffice\Form\MyAccount\ChangePasswordType;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;
use Netgen\Bundle\IbexaSiteApiBundle\Controller\Controller;
use Netgen\Bundle\SiteBundle\Helper\MailHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\Exception\RfcComplianceException;

use function count;
use function explode;

final class UserCredentials extends Controller
{
    public function __construct(
        private readonly Repository $repository,
        private readonly UserService $userService,
        private readonly SecurityTokenRepository $tokenRepository,
        private readonly MailHelper $mailHelper,
        private readonly ConfigResolverInterface $configResolver,
    ) {}

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        /** @var \Ibexa\Core\MVC\Symfony\Security\User $sfUser */
        $sfUser = $this->getUser();
        $user = $sfUser->getAPIUser();

        $emailForm = $this->createForm(ChangeEmailType::class, $user);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            if ($this->tokenRepository->findUserToken($user, SecurityTokenType::EmailChangeConfirmation) !== null) {
                $this->addFlash('notice', 'my_account.change_email.already_requested');

                return $this->redirectToRoute('backoffice_my_account_user_credentials');
            }

            /** @var \App\Backoffice\Form\MyAccount\ChangeEmailData $data */
            $data = $emailForm->getData();
            $email = $data->email;

            if ($email === null) {
                return $this->redirectToRoute('backoffice_my_account_user_credentials');
            }

            if ($email === $user->email) {
                $this->addFlash('notice', 'my_account.change_email.same_email');

                return $this->redirectToRoute('backoffice_my_account_user_credentials');
            }

            try {
                $this->userService->loadUserByEmail($email);
                $this->addFlash('notice', 'my_account.change_email.email_exists');

                return $this->redirectToRoute('backoffice_my_account_user_credentials');
            } catch (NotFoundException) {
                // Do nothing
            }

            $token = new SecurityToken(
                $user->id,
                SecurityTokenType::EmailChangeConfirmation,
                $this->configResolver->getParameter('email_change.token_validity_time_minutes', 'ngsite'),
                $email,
            );

            try {
                $this->mailHelper->sendMail(
                    $email,
                    'mail',
                    'backoffice/email/change_email_confirmation.html.twig',
                    [
                        'user' => $user,
                        'token' => $token->getToken(),
                    ],
                );
            } catch (RfcComplianceException) {
                $this->addFlash('notice', 'my_account.change_email.invalid_email');

                return $this->redirectToRoute('backoffice_my_account_user_credentials');
            }

            $this->tokenRepository->save($token, true);

            $this->addFlash('success', 'my_account.change_email.activation_mail_sent');

            return $this->redirectToRoute('backoffice_my_account_user_credentials');
        }

        $passwordForm = $this->createForm(ChangePasswordType::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();

            if ($data['password'] !== null) {
                $userUpdateStruct = $this->userService->newUserUpdateStruct();
                $userUpdateStruct->password = $data['password'];

                $this->repository->sudo(
                    fn (): User => $this->userService->updateUser($user, $userUpdateStruct),
                );

                $this->addFlash(
                    'success',
                    'my_account.user_successfully_updated',
                );
            }

            return $this->redirectToRoute('backoffice_my_account_user_credentials');
        }

        $currentUserEmail = $user->email;
        $emailParts = explode('@', $currentUserEmail);

        if (count($emailParts) !== 2) {
            throw new BadRequestHttpException("User's email address is not valid.");
        }

        return $this->render(
            'backoffice/my_account/user_credentials.html.twig',
            [
                'email_domain' => $emailParts[1],
                'email_form' => $emailForm->createView(),
                'password_form' => $passwordForm->createView(),
                'user' => $user,
            ],
        );
    }
}
