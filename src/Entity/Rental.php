<?php

namespace App\Entity;

use App\Entity\Locker;
use App\Entity\Penalty;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rental.
 *
 * @ORM\Table(name="rental")
 * @ORM\Entity(repositoryClass="App\Repository\RentalRepository")
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
     * @ORM\Column(name="end_at", type="date")
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
     * @var string
     *
     * @ORM\Column(name="renew_code", type="string")
     */
    private $renewCode;

    /**
     * @var Locker
     *
     * @ORM\ManyToOne(targetEntity="Seta\LockerBundle\Entity\Locker", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locker;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Seta\UserBundle\Entity\User", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Penalty
     *
     * @ORM\OneToOne(targetEntity="Seta\PenaltyBundle\Entity\Penalty", mappedBy="rental")
     */
    private $penalty;

    /**
     * Rental constructor.
     */
    public function __construct()
    {
        $this->isRenewable = true;
        $this->startAt = new \DateTime('now');
        $this->generateNewCode();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startAt.
     *
     * @param \DateTime $startAt
     *
     * @return Rental
     */
    public function setStartAt($startAt)
    {
        $this->startAt = clone $startAt;

        return $this;
    }

    /**
     * Get startAt.
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt.
     *
     * @param \DateTime $endAt
     *
     * @return Rental
     */
    public function setEndAt($endAt)
    {
        $this->endAt = clone $endAt;
        $this->endAt->setTime(0, 0, 0);

        return $this;
    }

    /**
     * Get endAt.
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Get days left.
     *
     * @return int
     */
    public function getDaysLeft()
    {
        $now = new \DateTime('today');

        $diff = $now->diff($this->getEndAt());

        if ($diff->invert) {
            return 0;
        }

        return $diff->days;
    }

    /**
     * Get days late.
     *
     * @return int
     */
    public function getDaysLate()
    {
        $now = new \DateTime('today');

        $diff = $now->diff($this->getEndAt());

        if (!$diff->invert) {
            return 0;
        }

        return $diff->days;
    }

    /**
     * Get if rental is expired.
     *
     * @return bool
     */
    public function getIsExpired()
    {
        return new \DateTime('today') > $this->getEndAt();
    }

    /**
     * Set returnAt.
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
     * Get returnAt.
     *
     * @return \DateTime
     */
    public function getReturnAt()
    {
        return $this->returnAt;
    }

    /**
     * Set isRenewable.
     *
     * @param bool $isRenewable
     *
     * @return Rental
     */
    public function setIsRenewable($isRenewable)
    {
        $this->isRenewable = $isRenewable;

        return $this;
    }

    /**
     * Get isRenewable.
     *
     * @return bool
     */
    public function getIsRenewable()
    {
        return $this->isRenewable;
    }

    /**
     * Set locker.
     *
     * @param \App\Entity\Locker $locker
     *
     * @return Rental
     */
    public function setLocker(\App\Entity\Locker $locker = null)
    {
        $this->locker = $locker;

        return $this;
    }

    /**
     * Get locker.
     *
     * @return \App\Entity\Locker
     */
    public function getLocker()
    {
        return $this->locker;
    }

    /**
     * Set user.
     *
     * @param \App\Entity\User $user
     *
     * @return Rental
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set penalty.
     *
     * @param \App\Entity\Penalty $penalty
     *
     * @return Rental
     */
    public function setPenalty(\App\Entity\Penalty $penalty = null)
    {
        $this->penalty = $penalty;

        return $this;
    }

    /**
     * Get penalty.
     *
     * @return \App\Entity\Penalty
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * Genera un nuevo código de renovación.
     *
     * @return Rental
     */
    private function generateNewCode()
    {
        $this->renewCode = preg_replace('#[+/]#', '-', base64_encode(openssl_random_pseudo_bytes(30)));

        return $this;
    }

    /**
     * Set renewCode.
     *
     * @param string $renewCode
     *
     * @return Rental
     */
    public function setRenewCode($renewCode)
    {
        $this->renewCode = $renewCode;

        return $this;
    }

    /**
     * Get renewCode.
     *
     * @return string
     */
    public function getRenewCode()
    {
        return $this->renewCode;
    }
}
