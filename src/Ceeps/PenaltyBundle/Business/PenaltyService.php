<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 15:34
 */

namespace Ceeps\PenaltyBundle\Business;


use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\PenaltyBundle\Repository\PenaltyRepository;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class PenaltyService
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var PenaltyRepository
     */
    private $penaltyRepository;

    /**
     * PenaltyService constructor.
     */
    public function __construct(EntityManager $manager, PenaltyRepository $penaltyRepository)
    {
        $this->manager = $manager;
        $this->penaltyRepository = $penaltyRepository;
    }

    public function penalizeUser(User $user, \DateTime $start, \DateTime $end, $comment)
    {
        /** @var Penalty $penalty */
        $penalty = $this->penaltyRepository->createNew();
        $penalty->setStartAt($start);
        $penalty->setEndAt($end);
        $penalty->setComment($comment);
        $penalty->setUser($user);

        $user->setIsPenalized(true);

        $this->manager->persist($penalty);
        $this->manager->flush();
    }
}