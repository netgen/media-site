<?php

declare(strict_types=1);

namespace App\Logger;

use App\Enums\Log\Module;
use App\Enums\Log\Severity;
use App\Messenger\Message\Logger\LogAction;
use DateTimeImmutable;
use Ibexa\Core\MVC\Symfony\Security\UserInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

final class ActionLogger implements ActionLoggerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private Security $security,
    ) {}

    /**
     * @param mixed[] $context
     */
    public function emergency(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Emergency, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function alert(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Alert, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function critical(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Critical, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function error(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Error, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function warning(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Warning, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function notice(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Notice, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function info(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Info, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    public function debug(Module $module, string $message, array $context = []): void
    {
        $this->log(Severity::Debug, $module, $message, $context);
    }

    /**
     * @param mixed[] $context
     */
    private function log(Severity $severity, Module $module, string $message, array $context = []): void
    {
        $symfonyUser = $this->security->getUser();
        if (!$symfonyUser instanceof UserInterface) {
            $user = null;
        } else {
            $user = $symfonyUser->getAPIUser();
        }

        $logAction = new LogAction(
            new DateTimeImmutable(),
            $severity,
            $module,
            $user?->email ?? '',
            $message,
            $context,
        );

        $this->messageBus->dispatch($logAction);
    }
}
