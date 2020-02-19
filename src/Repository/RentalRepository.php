<?php

namespace App\Repository;

use App\Entity\Locker;
use App\Entity\Rental;
use App\Exception\NotFoundRentalException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Repository\RentalRepositoryInterface;

/**
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository implements RentalRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    public function getLastRentals(User $user, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.user', ':user'))
            ->orderBy('o.startAt', 'DESC')
            ->setParameter('user', $user)
            ->setMaxResults($limit)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function getCurrentRental(Locker $locker)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.user', ':user'))
            ->andWhere($queryBuilder->expr()->eq('o.locker', ':locker'))
            ->andWhere($queryBuilder->expr()->isNull('o.returnAt'))
            ->setParameter('user', $locker->getOwner())
            ->setParameter('locker', $locker)
        ;

        $rental = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$rental) {
            throw new NotFoundRentalException();
        }

        return $rental;
    }

    public function getExpireOnDateRentals(\DateTime $on)
    {
        $start = clone $on;
        $end = clone $on;
        $start->setTime(0, 0, 0);
        $end->setTime(23, 59, 59);

        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->between('o.endAt', ':start', ':end'))
            ->andWhere($queryBuilder->expr()->isNull('o.returnAt'))
            ->setParameter('start', $start)
            ->setParameter('end', $end)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
