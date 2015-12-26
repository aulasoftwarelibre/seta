<?php

namespace Ceeps\RentalBundle\Entity;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rental
 *
 * @ORM\Table(name="rental")
 * @ORM\Entity(repositoryClass="Ceeps\RentalBundle\Repository\RentalRepository")
 */
class Rental
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime")
     */
    private $endAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_at", type="datetime", nullable=true)
     */
    private $returnAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_renewable", type="boolean")
     */
    private $isRenewable;

    /**
     * @var Locker
     *
     * @ORM\ManyToOne(targetEntity="Ceeps\LockerBundle\Entity\Locker", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locker;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ceeps\UserBundle\Entity\User", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Penalty
     *
     * @ORM\OneToOne(targetEntity="Ceeps\PenaltyBundle\Entity\Penalty", mappedBy="rental")
     */
    private $penalty;

    /**
     * Rental constructor.
     */
    public function __construct()
    {
        $this->isRenewable = true;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startAt
     *
     * @param \DateTime $startAt
     *
     * @return Rental
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt
     *
     * @param \DateTime $endAt
     *
     * @return Rental
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Get endAt
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set returnAt
     *
     * @param \DateTime $returnAt
     *
     * @return Rental
     */
    public function setReturnAt($returnAt)
    {
        $this->returnAt = $returnAt;

        return $this;
    }

    /**
     * Get returnAt
     *
     * @return \DateTime
     */
    public function getReturnAt()
    {
        return $this->returnAt;
    }

    /**
     * Set isRenewable
     *
     * @param boolean $isRenewable
     *
     * @return Rental
     */
    public function setIsRenewable($isRenewable)
    {
        $this->isRenewable = $isRenewable;

        return $this;
    }

    /**
     * Get isRenewable
     *
     * @return bool
     */
    public function getIsRenewable()
    {
        return $this->isRenewable;
    }

    /**
     * Set locker
     *
     * @param \Ceeps\LockerBundle\Entity\Locker $locker
     *
     * @return Rental
     */
    public function setLocker(\Ceeps\LockerBundle\Entity\Locker $locker = null)
    {
        $this->locker = $locker;

        return $this;
    }

    /**
     * Get locker
     *
     * @return \Ceeps\LockerBundle\Entity\Locker
     */
    public function getLocker()
    {
        return $this->locker;
    }

    /**
     * Set user
     *
     * @param \Ceeps\UserBundle\Entity\User $user
     *
     * @return Rental
     */
    public function setUser(\Ceeps\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ceeps\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set penalty
     *
     * @param \Ceeps\PenaltyBundle\Entity\Penalty $penalty
     *
     * @return Rental
     */
    public function setPenalty(\Ceeps\PenaltyBundle\Entity\Penalty $penalty = null)
    {
        $this->penalty = $penalty;

        return $this;
    }

    /**
     * Get penalty
     *
     * @return \Ceeps\PenaltyBundle\Entity\Penalty
     */
    public function getPenalty()
    {
        return $this->penalty;
    }
}
