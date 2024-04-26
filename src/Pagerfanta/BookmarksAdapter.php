<?php

declare(strict_types=1);

namespace App\Pagerfanta;

use App\Doctrine\Repository\BookmarkRepository;
use DateTimeImmutable;
use Pagerfanta\Adapter\AdapterInterface;

final class BookmarksAdapter implements AdapterInterface
{
    public function __construct(
        private BookmarkRepository $repository,
        private int $userId,
        private ?int $contentTypeId = null,
        private ?string $searchText = null,
        private ?DateTimeImmutable $dateFrom = null,
        private ?DateTimeImmutable $dateTo = null,
    ) {}

    public function getNbResults(): int
    {
        return $this->repository->filterUserBookmarksCount(
            $this->userId,
            $this->contentTypeId,
            $this->searchText,
            $this->dateFrom,
            $this->dateTo,
        );
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return iterable<int, \App\Doctrine\Entity\Bookmark>
     */
    public function getSlice($offset, $length): iterable
    {
        return $this->repository->filterUserBookmarks(
            $this->userId,
            $this->contentTypeId,
            $this->searchText,
            $this->dateFrom,
            $this->dateTo,
            $offset,
            $length,
        );
    }
}
