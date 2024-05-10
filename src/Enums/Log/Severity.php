<?php

declare(strict_types=1);

namespace App\Enums\Log;

enum Severity: string
{
    case Emergency = 'emergency';

    case Alert = 'alert';

    case Critical = 'critical';

    case Error = 'error';

    case Warning = 'warning';

    case Notice = 'notice';

    case Info = 'info';

    case Debug = 'debug';
}
