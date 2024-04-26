<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<object>
 */
abstract class Repository extends ServiceEntityRepository
{
    public function save(object $entity, bool $flush = false, bool $refresh = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->flush();

            if ($refresh) {
                $this->getEntityManager()->refresh($entity);
            }
        }
    }

    public function remove(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
