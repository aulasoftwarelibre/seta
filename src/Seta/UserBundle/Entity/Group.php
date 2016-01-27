<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Class Group.
 *
 * @ORM\Entity()
 * @ORM\Table(name="fos_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Seta\UserBundle\Entity\User", mappedBy="groups")
     */
    protected $users;

    public function __construct($name, array $roles)
    {
        parent::__construct($name, $roles);

        $this->users = new ArrayCollection();
    }

    /**
     * Add user.
     *
     * @param \Seta\UserBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\Seta\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \Seta\UserBundle\Entity\User $user
     */
    public function removeUser(\Seta\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get group name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
