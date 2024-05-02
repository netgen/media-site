<?php

declare(strict_types=1);

namespace App\Backoffice\Enums;

enum SecurityTokenType: string
{
    case EmailChangeConfirmation = 'email_change_confirmation';
}
