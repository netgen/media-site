<?php

declare(strict_types=1);

namespace App\Enums\Log;

enum Module: string
{
    case Notifications = 'Notifications';

    case Login = 'Login';
}
