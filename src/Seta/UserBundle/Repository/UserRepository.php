<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 18:55.
 */
namespace Seta\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    protected function getAllEmailAddress()
    {
        return $this->createQueryBuilder('o')
            ->select('o.email')
            ->distinct(true)
            ;
    }

    public function findAllEmailAddress()
    {
        return $this->getAllEmailAddress()
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findAllEmailAddressWithActiveRental()
    {
        return $this->getAllEmailAddress()
            ->leftJoin('o.rentals', 'r')
            ->where('r.returnAt IS NULL')
            ->getQuery()
            ->getArrayResult()
            ;

    }
}
