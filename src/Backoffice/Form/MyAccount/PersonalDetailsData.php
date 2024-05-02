<?php

declare(strict_types=1);

namespace App\Backoffice\Form\MyAccount;

use Ibexa\Core\FieldType\Image\Value;
use Symfony\Component\Form\AbstractType;

final class PersonalDetailsData extends AbstractType
{
    public function __construct(
        public readonly ?Value $image = null,
        public bool $removeImage = false,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {}
}
