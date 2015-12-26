<?php

namespace Ceeps\PenaltyBundle\Entity;

use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Penalty
 *
 * @ORM\Table(name="penalty")
 * @ORM\Entity(repositoryClass="Ceeps\PenaltyBundle\Repository\PenaltyRepository")
 */
class Penalty
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
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ceeps\UserBundle\Entity\User", inversedBy="penalties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Rental
     *
     * @ORM\OneToOne(targetEntity="Ceeps\RentalBundle\Entity\Rental", inversedBy="penalty", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $rental;

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
     * @param \Ceeps\UserBundle\Entity\User $user
     *
     * @return Penalty
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
     * Set rental
     *
     * @param \Ceeps\RentalBundle\Entity\Rental $rental
     *
     * @return Penalty
     */
    public function setRental(\Ceeps\RentalBundle\Entity\Rental $rental = null)
    {
        $this->rental = $rental;

        return $this;
    }

    /**
     * Get rental
     *
     * @return \Ceeps\RentalBundle\Entity\Rental
     */
    public function getRental()
    {
        return $this->rental;
    }
}
