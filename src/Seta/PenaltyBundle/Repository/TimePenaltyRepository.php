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


use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\RentalBundle\Entity\Rental;
use Seta\ResourceBundle\Doctrine\ORM\EntityRepository;
use Seta\UserBundle\Entity\User;

class TimePenaltyRepository extends EntityRepository
{
    /**
     * Crea una nueva instancia de sanción
     *
     * @param User $user
     * @param \DateTime $end
     * @param $comment
     * @param Rental|null $rental
     * @return TimePenalty
     */
    public function createFromData(User $user, \DateTime $end, $comment, Rental $rental = null)
    {
        /** @var TimePenalty $penalty */
        $penalty = $this->createNew();
        $penalty->setComment($comment);
        $penalty->setEndAt($end);
        $penalty->setUser($user);
        if ($rental) {
            $penalty->setRental($rental);
        }

        return $penalty;
    }
}