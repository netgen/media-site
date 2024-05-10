<?php

declare(strict_types=1);

namespace App\Messenger\Message\Logger;

use App\Enums\Log\Module;
use App\Enums\Log\Severity;
use DateTimeImmutable;

final class LogAction
{
    /**
     * @param mixed[] $context
     */
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly Severity $severity,
        public readonly Module $module,
        public readonly string $email,
        public readonly string $message,
        public readonly array $context = [],
    ) {}
}
