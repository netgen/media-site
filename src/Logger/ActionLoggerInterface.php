<?php

declare(strict_types=1);

namespace App\Logger;

use App\Enums\Log\Module;

interface ActionLoggerInterface
{
    /**
     * System is unusable.
     *
     * @param mixed[] $context
     */
    public function emergency(Module $module, string $message, array $context = []): void;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param mixed[] $context
     */
    public function alert(Module $module, string $message, array $context = []): void;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param mixed[] $context
     */
    public function critical(Module $module, string $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param mixed[] $context
     */
    public function error(Module $module, string $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param mixed[] $context
     */
    public function warning(Module $module, string $message, array $context = []): void;

    /**
     * Normal but significant events.
     *
     * @param mixed[] $context
     */
    public function notice(Module $module, string $message, array $context = []): void;

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param mixed[] $context
     */
    public function info(Module $module, string $message, array $context = []): void;

    /**
     * Detailed debug information.
     *
     * @param mixed[] $context
     */
    public function debug(Module $module, string $message, array $context = []): void;
}
