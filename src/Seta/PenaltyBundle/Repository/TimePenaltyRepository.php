<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\PenaltyBundle\Repository;

use Seta\PenaltyBundle\Entity\Penalty;
use Seta\ResourceBundle\Doctrine\ORM\EntityRepository;

class TimePenaltyRepository extends EntityRepository
{
    public function getQueryExpiredPenalties()
    {
        $qb = $this->getQueryBuilder();
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
