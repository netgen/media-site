<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use DateTimeImmutable;
use Netgen\IbexaSiteApi\API\Values\Location;

class Bookmark
{
    private int $id;

    private DateTimeImmutable $createdAt;

    private ?Location $location = null;

    private function __construct(
        private int $userId,
        private int $contentId,
        private int $locationId,
        private int $contentTypeId,
        private string $title,
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getContentId(): int
    {
        return $this->contentId;
    }

    public function setContentId(int $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getContentTypeId(): int
    {
        return $this->contentTypeId;
    }

    public function setContentTypeId(int $contentTypeId): self
    {
        $this->contentTypeId = $contentTypeId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public static function create(
        int $userId,
        int $contentId,
        int $locationId,
        int $contentTypeId,
        string $title,
    ): self {
        return new self($userId, $contentId, $locationId, $contentTypeId, $title);
    }
}
