<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use Ibexa\Contracts\Core\Repository\Values\User\User;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;
use Traversable;

use function count;
use function explode;
use function iterator_to_array;

final class ChangeEmailDataMapper implements DataMapperInterface
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof User) {
            return;
        }

        $email = $viewData->email;

        $emailParts = explode('@', $viewData->email);

        if (count($emailParts) !== 2) {
            throw new BadRequestHttpException("User's email address is not valid.");
        }

        $forms = iterator_to_array($forms);

        $forms['email_username']->setData($email);
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        /** @var \Ibexa\Core\MVC\Symfony\Security\User $symfonyUser */
        $symfonyUser = $this->security->getUser();
        $user = $symfonyUser->getAPIUser();

        $emailParts = explode('@', $user->email);

        if (count($emailParts) !== 2) {
            throw new BadRequestHttpException("User's email address is not valid.");
        }

        $forms = iterator_to_array($forms);

        $viewData = new ChangeEmailData(
            $forms['email_username']->getData(),
        );
    }
}
