<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

final class ChangeEmailData
{
    public function __construct(
        public readonly ?string $email = null,
    ) {}
}
