<?php

namespace Seta\PenaltyBundle\Entity;

use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Penalty
 *
 * @ORM\Table(name="penalty")
 * @ORM\Entity(repositoryClass="Seta\PenaltyBundle\Repository\PenaltyRepository")
 */
class Penalty
{
    const ACTIVE = 'penalty.active';
    const DONE = 'penalty.done';

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
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Seta\UserBundle\Entity\User", inversedBy="penalties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Rental
     *
     * @ORM\OneToOne(targetEntity="Seta\RentalBundle\Entity\Rental", inversedBy="penalty", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $rental;

    /**
     * Penalty constructor.
     */
    public function __construct()
    {
        $this->startAt = new \DateTime('now');
        $this->status = self::ACTIVE;
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
     * @return Penalty
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
     * @return Penalty
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Penalty
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \Seta\UserBundle\Entity\User $user
     *
     * @return Penalty
     */
    public function setUser(\Seta\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Seta\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rental
     *
     * @param \Seta\RentalBundle\Entity\Rental $rental
     *
     * @return Penalty
     */
    public function setRental(\Seta\RentalBundle\Entity\Rental $rental = null)
    {
        $this->rental = $rental;

        return $this;
    }

    /**
     * Get rental
     *
     * @return \Seta\RentalBundle\Entity\Rental
     */
    public function getRental()
    {
        return $this->rental;
    }

    /**
     * @param null $today
     * @return \DateTime
     */
    static public function getEndSeasonPenalty($today = null)
    {
        if (!$today) {
            $today = new \DateTime('today');
        }

        $endSeason = new \DateTime("september 1 midnight");
        if ($today >= $endSeason) {
            return new \DateTime('next year september 1 midnight');
        }

        return $endSeason;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Penalty
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
