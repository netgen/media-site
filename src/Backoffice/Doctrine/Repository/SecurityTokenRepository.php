<?php

declare(strict_types=1);

namespace App\Backoffice\Doctrine\Repository;

use App\Backoffice\Doctrine\Entity\SecurityToken;
use App\Backoffice\Enums\SecurityTokenType;
use App\Repository\Repository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Ibexa\Contracts\Core\Repository\Values\User\User;

/**
 * @method \App\Backoffice\Doctrine\Entity\SecurityToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Backoffice\Doctrine\Entity\SecurityToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Backoffice\Doctrine\Entity\SecurityToken[] findAll()
 * @method \App\Backoffice\Doctrine\Entity\SecurityToken[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SecurityTokenRepository extends Repository
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, SecurityToken::class);
    }

    public function findUserToken(User $user, SecurityTokenType $tokenType): ?SecurityToken
    {
        $query = $this->createQueryBuilder('t');

        $query
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('t.userId', ':userId'),
                    $query->expr()->eq('t.tokenType', ':tokenType'),
                    $query->expr()->eq('t.isUsed', 0),
                    $query->expr()->gt('t.expiryDate', ':expiryDate'),
                ),
            )->setParameter('userId', $user->id)
            ->setParameter('tokenType', $tokenType->value)
            ->setParameter('expiryDate', new DateTimeImmutable());

        return $query->getQuery()->getOneOrNullResult();
    }
}
