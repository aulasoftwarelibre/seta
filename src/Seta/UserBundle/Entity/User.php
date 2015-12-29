<?php

namespace Seta\UserBundle\Entity;


use Seta\RentalBundle\Entity\Queue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="Seta\UserBundle\Repository\UserRepository")
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
     * @ORM\OneToMany(targetEntity="Seta\RentalBundle\Entity\Rental", mappedBy="user")
     */
    private $rentals;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Seta\PenaltyBundle\Entity\Penalty", mappedBy="user")
     */
    private $penalties;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Seta\LockerBundle\Entity\Locker", mappedBy="owner")
     */
    private $lockers;

    /**
     * @var Queue
     *
     * @ORM\OneToOne(targetEntity="Seta\RentalBundle\Entity\Queue", mappedBy="user")
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
     * @param \Seta\RentalBundle\Entity\Rental $rental
     *
     * @return User
     */
    public function addRental(\Seta\RentalBundle\Entity\Rental $rental)
    {
        $this->rentals[] = $rental;

        return $this;
    }

    /**
     * Remove rental
     *
     * @param \Seta\RentalBundle\Entity\Rental $rental
     */
    public function removeRental(\Seta\RentalBundle\Entity\Rental $rental)
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
     * @param \Seta\PenaltyBundle\Entity\Penalty $penalty
     *
     * @return User
     */
    public function addPenalty(\Seta\PenaltyBundle\Entity\Penalty $penalty)
    {
        $this->penalties[] = $penalty;

        return $this;
    }

    /**
     * Remove penalty
     *
     * @param \Seta\PenaltyBundle\Entity\Penalty $penalty
     */
    public function removePenalty(\Seta\PenaltyBundle\Entity\Penalty $penalty)
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
     * @param \Seta\LockerBundle\Entity\Locker $locker
     *
     * @return User
     */
    public function addLocker(\Seta\LockerBundle\Entity\Locker $locker)
    {
        $this->lockers[] = $locker;

        return $this;
    }

    /**
     * Remove locker
     *
     * @param \Seta\LockerBundle\Entity\Locker $locker
     */
    public function removeLocker(\Seta\LockerBundle\Entity\Locker $locker)
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
     * @param \Seta\RentalBundle\Entity\Queue $queue
     *
     * @return User
     */
    public function setQueue(\Seta\RentalBundle\Entity\Queue $queue = null)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get queue
     *
     * @return \Seta\RentalBundle\Entity\Queue
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
