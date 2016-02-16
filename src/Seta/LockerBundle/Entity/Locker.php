<?php

namespace Seta\LockerBundle\Entity;

use Seta\RentalBundle\Entity\Queue;
use Seta\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Locker.
 *
 * @ORM\Table(name="locker")
 * @ORM\Entity(repositoryClass="Seta\LockerBundle\Repository\LockerRepository")
 * @UniqueEntity(fields={"code"})
 */
class Locker
{
    const AVAILABLE = 'locker.available';
    const UNAVAILABLE = 'locker.unavailable';
    const RENTED = 'locker.rented';
    const RESERVED = 'locker.reserved';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=255)
     * @Assert\Length(max="1", min="1")
     */
    private $zone;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Seta\RentalBundle\Entity\Rental", mappedBy="locker")
     */
    private $rentals;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="Seta\UserBundle\Entity\User", inversedBy="locker")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * @var Queue
     * @ORM\OneToOne(targetEntity="Seta\RentalBundle\Entity\Queue", mappedBy="locker")
     */
    private $queue;

    /**
     * Locker constructor.
     */
    public function __construct()
    {
        $this->rentals = new ArrayCollection();
        $this->status = self::AVAILABLE;
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
     * Set code.
     *
     * @param string $code
     *
     * @return Locker
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Locker
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add rental.
     *
     * @param \Seta\RentalBundle\Entity\Rental $rental
     *
     * @return Locker
     */
    public function addRental(\Seta\RentalBundle\Entity\Rental $rental)
    {
        $this->rentals[] = $rental;

        return $this;
    }

    /**
     * Remove rental.
     *
     * @param \Seta\RentalBundle\Entity\Rental $rental
     */
    public function removeRental(\Seta\RentalBundle\Entity\Rental $rental)
    {
        $this->rentals->removeElement($rental);
    }

    /**
     * Get rentals.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRentals()
    {
        return $this->rentals;
    }

    /**
     * Set owner.
     *
     * @param \Seta\UserBundle\Entity\User $owner
     *
     * @return Locker
     */
    public function setOwner(\Seta\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return \Seta\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }

    /**
     * Set queue.
     *
     * @param \Seta\RentalBundle\Entity\Queue $queue
     *
     * @return Locker
     */
    public function setQueue(\Seta\RentalBundle\Entity\Queue $queue = null)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue.
     *
     * @return \Seta\RentalBundle\Entity\Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }
}
