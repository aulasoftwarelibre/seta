<?php

namespace Ceeps\LockerBundle\Repository;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\NotFreeLockerException;
use Ceeps\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class LockerRepository
 * @package Ceeps\LockerBundle\Repository
 */
class LockerRepository extends EntityRepository
{
    /**
     * Finds the first free locker
     *
     * @return mixed
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

        if (!$locker) {
            throw new NotFreeLockerException;
        }

        return $locker;
    }
}
