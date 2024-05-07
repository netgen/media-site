<?php

declare(strict_types=1);

namespace App\Backoffice\Doctrine\Entity;

use App\Backoffice\Enums\SecurityTokenType;
use DateTimeImmutable;

use function hash;
use function random_bytes;
use function sprintf;

class SecurityToken
{
    private int $id;
    private string $token;
    private DateTimeImmutable $creationDate;
    private DateTimeImmutable $expiryDate;
    private bool $isUsed = false;

    public function __construct(
        private readonly int $userId,
        private readonly SecurityTokenType $tokenType,
        private readonly int $validity,
        private readonly ?string $data = null,
    ) {
        $this->token = hash('sha256', random_bytes(256));
        $this->creationDate = new DateTimeImmutable();
        $this->expiryDate = $this->creationDate->modify(sprintf('+%d seconds', $validity));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getExpiryDate(): DateTimeImmutable
    {
        return $this->expiryDate;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): SecurityTokenType
    {
        return $this->tokenType;
    }

    public function getValidity(): int
    {
        return $this->validity;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }
}
