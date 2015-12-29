<?php

namespace Ceeps\RentalBundle\Entity;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Queue
 *
 * @ORM\Table(name="queue")
 * @ORM\Entity(repositoryClass="Ceeps\RentalBundle\Repository\QueueRepository")
 */
class Queue
{
    const CREATED = 'queue.created';
    const CONFIRMED = 'queue.confirmed';

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
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmed_at", type="datetime", nullable=true)
     */
    private $confirmedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="Ceeps\UserBundle\Entity\User", inversedBy="queue")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Locker
     *
     * @ORM\OneToOne(targetEntity="Ceeps\LockerBundle\Entity\Locker", inversedBy="queue")
     * @ORM\JoinColumn(nullable=true)
     */
    private $locker;

    /**
     * Queue constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Queue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \Ceeps\UserBundle\Entity\User $user
     *
     * @return Queue
     */
    public function setUser(\Ceeps\UserBundle\Entity\User $user)
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
     * Set confirmedAt
     *
     * @param \DateTime $confirmedAt
     *
     * @return Queue
     */
    public function setConfirmedAt($confirmedAt)
    {
        $this->confirmedAt = $confirmedAt;

        return $this;
    }

    /**
     * Get confirmedAt
     *
     * @return \DateTime
     */
    public function getConfirmedAt()
    {
        return $this->confirmedAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Queue
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

    /**
     * Set locker
     *
     * @param \Ceeps\LockerBundle\Entity\Locker $locker
     *
     * @return Queue
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
}
