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

use App\Entity\FinancialPenalty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FinancialPenalty|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinancialPenalty|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinancialPenalty[]    findAll()
 * @method FinancialPenalty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialPenaltyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinancialPenalty::class);
    }
}
