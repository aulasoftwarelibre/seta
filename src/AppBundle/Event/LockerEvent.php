<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/11/15
 * Time: 19:15
 */

namespace AppBundle\Event;


use AppBundle\Entity\Locker;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class LockerEvent extends Event
{
    /**
     * @var Locker
     */
    private $locker;
    /**
     * @var User
     */
    private $user;

    /**
     * LockerEvent constructor.
     */
    public function __construct(Locker $locker, User $user)
    {
        $this->locker = $locker;
        $this->user = $user;
    }

    /**
     * @return Locker
     */
    public function getLocker()
    {
        return $this->locker;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}