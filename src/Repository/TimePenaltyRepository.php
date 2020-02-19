<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\TimePenalty;
use App\Entity\Penalty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Seta\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @method TimePenalty|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimePenalty|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimePenalty[]    findAll()
 * @method TimePenalty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimePenaltyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimePenalty::class);
    }

    public function getQueryExpiredPenalties()
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->andWhere($qb->expr()->eq('o.status', ':status'))
            ->andWhere($qb->expr()->lte('o.endAt', ':now'))
            ->setParameter('status', Penalty::ACTIVE)
            ->setParameter('now', new \DateTime('today'))
        ;

        return $qb;
    }

    public function findExpiredPenalties()
    {
        return $this->getQueryExpiredPenalties()->getQuery()->getResult();
    }
}
