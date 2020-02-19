<?php

namespace App\Repository;

use App\Entity\Locker;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Locker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locker[]    findAll()
 * @method Locker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locker::class);
    }

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
        $qb = $this->createQueryBuilder('o');

        $qb
            ->andWhere($qb->expr()->eq('o.status', ':enabled'))
            ->setParameter('enabled', Locker::AVAILABLE)
            ->setMaxResults(1)
        ;

        return $qb;
    }
}
