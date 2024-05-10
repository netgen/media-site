<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\Log\Module;
use App\Enums\Log\Severity;
use App\Messenger\Message\Logger\LogAction;
use DateTimeImmutable;

class Log
{
    private int $id;

    /**
     * @param mixed[] $context
     */
    private function __construct(
        private DateTimeImmutable $date,
        private Severity $severity,
        private Module $module,
        private string $email,
        private string $message,
        private array $context = [],
    ) {}

    public static function fromLogAction(LogAction $logAction): self
    {
        return new self(
            $logAction->date,
            $logAction->severity,
            $logAction->module,
            $logAction->email,
            $logAction->message,
            $logAction->context,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getSeverity(): Severity
    {
        return $this->severity;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
