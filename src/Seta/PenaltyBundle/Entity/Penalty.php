<?php

namespace Seta\PenaltyBundle\Entity;

use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Penalty
 *
 * @ORM\Table(name="penalty")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Entity(repositoryClass="Seta\PenaltyBundle\Repository\PenaltyRepository")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"time" = "TimePenalty", "financial" = "FinancialPenalty"})
 */
abstract class Penalty
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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Seta\UserBundle\Entity\User", inversedBy="penalties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var Rental
     *
     * @ORM\OneToOne(targetEntity="Seta\RentalBundle\Entity\Rental", inversedBy="penalty", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $rental;

    /**
     * Penalty constructor.
     */
    public function __construct()
    {
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
