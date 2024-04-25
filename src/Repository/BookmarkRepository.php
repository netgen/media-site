<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bookmark;
use DateTimeImmutable;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method \App\Entity\Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Bookmark[] findAll()
 * @method \App\Entity\Bookmark[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BookmarkRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function filterUserBookmarksCount(
        int $userId,
        ?int $contentTypeId = null,
        ?string $searchText = null,
        ?DateTimeImmutable $dateFrom = null,
        ?DateTimeImmutable $dateTo = null,
    ): int {
        $query = $this->createQueryBuilder('b');

        $query->select('COUNT(b)');

        $this->addFiltersToQuery($query, $userId, $contentTypeId, $searchText, $dateFrom, $dateTo);

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @return iterable<int, \App\Entity\Bookmark>
     */
    public function filterUserBookmarks(
        int $userId,
        ?int $contentTypeId = null,
        ?string $searchText = null,
        ?DateTimeImmutable $dateFrom = null,
        ?DateTimeImmutable $dateTo = null,
        int $offset = 0,
        ?int $limit = null,
    ): iterable {
        $query = $this->createQueryBuilder('b');

        $this->addFiltersToQuery($query, $userId, $contentTypeId, $searchText, $dateFrom, $dateTo);

        $query
            ->orderBy('b.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function updateBookmarkNames(int $contentId, string $title): void
    {
        $query = $this->createQueryBuilder('b');

        $query->update()
            ->set('b.title', ':title')
            ->where($query->expr()->eq('b.contentId', ':contentId'))
            ->setParameter('title', $title)
            ->setParameter('contentId', $contentId)
            ->getQuery()->execute();
    }

    private function addFiltersToQuery(
        QueryBuilder $query,
        ?int $userId = null,
        ?int $contentTypeId = null,
        ?string $searchText = null,
        ?DateTimeImmutable $dateFrom = null,
        ?DateTimeImmutable $dateTo = null,
    ): void {
        if ($userId !== null) {
            $query->andWhere(
                $query->expr()->eq('b.userId', ':userId'),
            )->setParameter('userId', $userId);
        }

        if ($contentTypeId !== 0 && $contentTypeId !== null) {
            $query->andWhere(
                $query->expr()->eq('b.contentTypeId', ':contentTypeId'),
            )->setParameter('contentTypeId', $contentTypeId);
        }

        if ($searchText !== '' && $searchText !== null) {
            $query->andWhere(
                $query->expr()->like('b.title', ':searchText'),
            )->setParameter('searchText', '%' . $searchText . '%');
        }

        if ($dateFrom !== null) {
            $query->andWhere(
                $query->expr()->gte('b.createdAt', ':dateFrom'),
            )->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo !== null) {
            $query->andWhere(
                $query->expr()->lt('b.createdAt', ':dateTo'),
            )->setParameter('dateTo', $dateTo);
        }
    }
}
