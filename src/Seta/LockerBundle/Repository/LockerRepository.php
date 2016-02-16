<?php

namespace Seta\LockerBundle\Repository;

use Seta\LockerBundle\Entity\Locker;
use Seta\LockerBundle\Entity\Zone;
use Seta\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Class LockerRepository.
 */
class LockerRepository extends EntityRepository
{
    /**
     * Encuentra la primera taquilla libre.
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneFreeLocker()
    {
        $qb = $this->getOneFreeLocker();

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Encuentra la primera taquilla libre en una zona.
     *
     * @param Zone $zone
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneFreeZoneLocker(Zone $zone)
    {
        $qb = $this->getOneFreeLocker();

        $qb
            ->andWhere($qb->expr()->eq('o.zone', ':zone'))
            ->setParameter('zone', $zone)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Devuelve la consulta para buscar una taquilla libre.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getOneFreeLocker()
    {
        $qb = $this->getQueryBuilder();

        $qb
            ->andWhere($qb->expr()->eq('o.status', ':enabled'))
            ->setParameter('enabled', Locker::AVAILABLE)
            ->setMaxResults(1)
        ;

        return $qb;
    }
}
