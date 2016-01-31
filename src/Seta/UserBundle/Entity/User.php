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
     * @var bool
     *
     * @ORM\Column(name="is_penalized", type="boolean", nullable=false)
     */
    private $isPenalized;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     */
    private $fullname;

    /**
     * @var string
     * @ORM\Column(name="nic", type="string", length=255, nullable=true)
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
     * @ORM\OneToOne(targetEntity="Seta\LockerBundle\Entity\Locker", mappedBy="owner")
     */
    private $locker;

    /**
     * @var Queue
     *
     * @ORM\OneToOne(targetEntity="Seta\RentalBundle\Entity\Queue", mappedBy="user")
     */
    private $queue;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Seta\UserBundle\Entity\Group", inversedBy="users")
     */
    protected $groups;

    /**
     * @var Faculty
     *
     * @ORM\ManyToOne(targetEntity="Seta\UserBundle\Entity\Faculty", inversedBy="students")
     */
    protected $faculty;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->groups = new ArrayCollection();
        $this->isPenalized = false;
        $this->penalties = new ArrayCollection();
        $this->rentals = new ArrayCollection();
    }

    /**
     * Add rental.
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
     * Add penalty.
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
     * Remove penalty.
     *
     * @param \Seta\PenaltyBundle\Entity\Penalty $penalty
     *
     * @return User
     */
    public function removePenalty(\Seta\PenaltyBundle\Entity\Penalty $penalty)
    {
        $this->penalties->removeElement($penalty);

        return $this;
    }

    /**
     * Get penalties.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPenalties()
    {
        return $this->penalties;
    }

    /**
     * Set isPenalized.
     *
     * @param bool $isPenalized
     *
     * @return User
     */
    public function setIsPenalized($isPenalized)
    {
        $this->isPenalized = $isPenalized;

        return $this;
    }

    /**
     * Get isPenalized.
     *
     * @return bool
     */
    public function getIsPenalized()
    {
        return $this->isPenalized;
    }

    /**
     * Set queue.
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
     * Get queue.
     *
     * @return \Seta\RentalBundle\Entity\Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set nic.
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
     * Get nic.
     *
     * @return string
     */
    public function getNic()
    {
        return $this->nic;
    }

    /**
     * Set fullname.
     *
     * @param string $fullname
     *
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname.
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set locker.
     *
     * @param \Seta\LockerBundle\Entity\Locker $locker
     *
     * @return User
     */
    public function setLocker(\Seta\LockerBundle\Entity\Locker $locker = null)
    {
        $this->locker = $locker;

        return $this;
    }

    /**
     * Get locker.
     *
     * @return \Seta\LockerBundle\Entity\Locker
     */
    public function getLocker()
    {
        return $this->locker;
    }

    /**
     * Set faculty
     *
     * @param \Seta\UserBundle\Entity\Faculty $faculty
     *
     * @return User
     */
    public function setFaculty(\Seta\UserBundle\Entity\Faculty $faculty = null)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get faculty
     *
     * @return \Seta\UserBundle\Entity\Faculty
     */
    public function getFaculty()
    {
        return $this->faculty;
    }
}
