<?php

namespace Ceeps\UserBundle\Entity;


use Ceeps\RentalBundle\Entity\Queue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="Ceeps\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_penalized", type="boolean", nullable=false)
     */
    private $isPenalized;

    /**
     * @var string
     * @ORM\Column(name="nic", type="string", length=255, nullable=false)
     */
    private $nic;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ceeps\RentalBundle\Entity\Rental", mappedBy="user")
     */
    private $rentals;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ceeps\PenaltyBundle\Entity\Penalty", mappedBy="user")
     */
    private $penalties;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Ceeps\LockerBundle\Entity\Locker", mappedBy="owner")
     */
    private $lockers;

    /**
     * @var Queue
     *
     * @ORM\OneToOne(targetEntity="Ceeps\RentalBundle\Entity\Queue", mappedBy="user")
     */
    private $queue;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->rentals = new ArrayCollection();
        $this->penalties = new ArrayCollection();
        $this->lockers = new ArrayCollection();
        $this->isPenalized = false;
    }

    /**
     * Add rental
     *
     * @param \Ceeps\RentalBundle\Entity\Rental $rental
     *
     * @return User
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
     * Add penalty
     *
     * @param \Ceeps\PenaltyBundle\Entity\Penalty $penalty
     *
     * @return User
     */
    public function addPenalty(\Ceeps\PenaltyBundle\Entity\Penalty $penalty)
    {
        $this->penalties[] = $penalty;

        return $this;
    }

    /**
     * Remove penalty
     *
     * @param \Ceeps\PenaltyBundle\Entity\Penalty $penalty
     */
    public function removePenalty(\Ceeps\PenaltyBundle\Entity\Penalty $penalty)
    {
        $this->penalties->removeElement($penalty);
    }

    /**
     * Get penalties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPenalties()
    {
        return $this->penalties;
    }

    /**
     * Set isPenalized
     *
     * @param boolean $isPenalized
     *
     * @return User
     */
    public function setIsPenalized($isPenalized)
    {
        $this->isPenalized = $isPenalized;

        return $this;
    }

    /**
     * Get isPenalized
     *
     * @return boolean
     */
    public function getIsPenalized()
    {
        return $this->isPenalized;
    }

    /**
     * Add locker
     *
     * @param \Ceeps\LockerBundle\Entity\Locker $locker
     *
     * @return User
     */
    public function addLocker(\Ceeps\LockerBundle\Entity\Locker $locker)
    {
        $this->lockers[] = $locker;

        return $this;
    }

    /**
     * Remove locker
     *
     * @param \Ceeps\LockerBundle\Entity\Locker $locker
     */
    public function removeLocker(\Ceeps\LockerBundle\Entity\Locker $locker)
    {
        $this->lockers->removeElement($locker);
    }

    /**
     * Get lockers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLockers()
    {
        return $this->lockers;
    }

    /**
     * Set queue
     *
     * @param \Ceeps\RentalBundle\Entity\Queue $queue
     *
     * @return User
     */
    public function setQueue(\Ceeps\RentalBundle\Entity\Queue $queue = null)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue
     *
     * @return \Ceeps\RentalBundle\Entity\Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set nic
     *
     * @param string $nic
     *
     * @return User
     */
    public function setNic($nic)
    {
        $this->nic = $nic;

        return $this;
    }

    /**
     * Get nic
     *
     * @return string
     */
    public function getNic()
    {
        return $this->nic;
    }
}
