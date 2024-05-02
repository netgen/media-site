<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

final class IbexaFieldValueValid extends Constraint
{
    public string $field;

    public string $contentType;
}
