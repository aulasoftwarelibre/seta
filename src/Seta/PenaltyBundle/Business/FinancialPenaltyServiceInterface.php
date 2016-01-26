<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Seta\PenaltyBundle\Business;


use Seta\UserBundle\Entity\User;

interface FinancialPenaltyServiceInterface
{
    public function penalizeUser(User $user, $amount, $comment);
}