<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use Ibexa\Contracts\Core\Repository\Values\User\User;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Security\Core\Security;
use Traversable;

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

        $forms = iterator_to_array($forms);

        $forms['email']->setData($viewData->email);
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        $viewData = new ChangeEmailData(
            $forms['email']->getData(),
        );
    }
}
