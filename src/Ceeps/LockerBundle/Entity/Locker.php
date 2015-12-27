<?php

namespace Ceeps\LockerBundle\Entity;

use Ceeps\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Locker
 *
 * @ORM\Table(name="locker")
 * @ORM\Entity(repositoryClass="Ceeps\LockerBundle\Repository\LockerRepository")
 */
class Locker
{
    const AVAILABLE = 'locker.available';

    const UNAVAILABLE = 'locker.unavailable';

    const RENTED = 'locker.rented';

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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ceeps\RentalBundle\Entity\Rental", mappedBy="locker")
     */
    private $rentals;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ceeps\UserBundle\Entity\User", inversedBy="lockers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * Locker constructor.
     */
    public function __construct()
    {
        $this->rentals = new ArrayCollection();
        $this->status = Locker::AVAILABLE;
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
     * Set code
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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set status
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
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add rental
     *
     * @param \Ceeps\RentalBundle\Entity\Rental $rental
     *
     * @return Locker
     */
    public function addRental(\Ceeps\RentalBundle\Entity\Rental $rental)
    {
        $this->rentals[] = $rental;

        return $this;
    }

    /**
     * Remove rental
     *
     * @param \Ceeps\RentalBundle\Entity\Rental $rental
     */
    public function removeRental(\Ceeps\RentalBundle\Entity\Rental $rental)
    {
        $this->rentals->removeElement($rental);
    }

    /**
     * Get rentals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRentals()
    {
        return $this->rentals;
    }

    /**
     * Set owner
     *
     * @param \Ceeps\UserBundle\Entity\User $owner
     *
     * @return Locker
     */
    public function setOwner(\Ceeps\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Ceeps\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
