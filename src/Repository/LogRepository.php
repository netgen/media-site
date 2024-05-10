<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Log;
use App\Enums\Log\Module;
use App\Enums\Log\Severity;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method \App\Entity\Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Log[] findAll()
 * @method \App\Entity\Log[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class LogRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @return \App\Entity\Log[]
     */
    public function search(
        ?string $message = null,
        ?Severity $severity = null,
        ?Module $module = null,
        ?string $email = null,
        ?DateTimeImmutable $dateFrom = null,
        ?DateTimeImmutable $dateTo = null,
    ): array {
        $query = $this->createQueryBuilder('l');

        if ($message !== null) {
            $query = $query->andWhere(
                $query->expr()->like('l.message', ':message'),
            )->setParameter('message', '%' . $message . '%');
        }

        if ($severity !== null) {
            $query = $query->andWhere(
                $query->expr()->eq('l.severity', ':severity'),
            )->setParameter('severity', $severity->value);
        }

        if ($module !== null) {
            $query = $query->andWhere(
                $query->expr()->like('l.module', ':module'),
            )->setParameter('module', '%' . $module->value . '%');
        }

        if ($email !== null) {
            $query = $query->andWhere(
                $query->expr()->like('l.email', ':email'),
            )->setParameter('email', '%' . $email . '%');
        }

        if ($dateFrom !== null) {
            $query = $query->andWhere(
                $query->expr()->gt('l.date', ':dateFrom'),
            )->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo !== null) {
            $query = $query->andWhere(
                $query->expr()->lt('l.date', ':dateTo'),
            )->setParameter('dateTo', $dateTo);
        }

        $query->addOrderBy('l.date', 'DESC');

        return $query->getQuery()->getResult();
    }
}
