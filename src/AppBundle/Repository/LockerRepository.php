<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 02/12/15
 * Time: 19:52
 */

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class LockerRepository extends EntityRepository
{
    public function queryFreeLocker()
    {
        $query = $this->getEntityManager()
            ->createQuery('
            SELECT locker FROM AppBundle:Locker locker
            WHERE locker.isEnabled = TRUE
            AND locker.user IS NULL
            ')
            ->setMaxResults(1)
        ;

        return $query;
    }

    public function findFreeLocker()
    {
        return $this->queryFreeLocker()->getResult();
    }

}