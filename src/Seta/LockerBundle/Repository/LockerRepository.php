<?php

namespace Seta\LockerBundle\Repository;

use Seta\LockerBundle\Entity\Locker;
use Seta\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class LockerRepository.
 */
class LockerRepository extends EntityRepository
{
    /**
     * Finds the first free locker.
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneFreeLocker()
    {
        $qb = $this->createQueryBuilder('l');

        $qb
            ->andWhere($qb->expr()->eq('l.status', ':enabled'))
            ->setParameter('enabled', Locker::AVAILABLE)
            ->setMaxResults(1)
        ;

        $locker = $qb->getQuery()->getOneOrNullResult();

        return $locker;
    }
}
