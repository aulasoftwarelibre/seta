<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 18:55.
 */
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

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
