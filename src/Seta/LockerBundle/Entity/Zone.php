<?php

namespace Seta\LockerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Zone
 *
 * @ORM\Table(name="zone")
 * @ORM\Entity(repositoryClass="Seta\LockerBundle\Repository\ZoneRepository")
 */
class Zone
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Seta\LockerBundle\Entity\Locker", mappedBy="zone")
     */
    private $lockers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lockers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Zone
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Zone
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add locker
     *
     * @param \Seta\LockerBundle\Entity\Locker $locker
     *
     * @return Zone
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
}
