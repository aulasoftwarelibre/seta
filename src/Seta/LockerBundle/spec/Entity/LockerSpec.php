<?php

namespace spec\Seta\LockerBundle\Entity;

use Seta\LockerBundle\Entity\Locker;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\LockerBundle\Entity\Locker');
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_code_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function its_code_is_mutable()
    {
        $this->setCode('1000A');
        $this->getCode()->shouldReturn('1000A');
    }
    
    function it_has_enabled_status_by_default()
    {
        $this->getStatus()->shouldReturn(Locker::AVAILABLE);
    }

    function its_status_is_mutable()
    {
        $this->setStatus(Locker::UNAVAILABLE);
        $this->getStatus()->shouldReturn(Locker::UNAVAILABLE);
    }

    function it_has_no_rentals_by_default()
    {
        $this->getRentals()->shouldHaveCount(0);
    }

    function it_can_add_a_rental(Rental $rental)
    {
        $this->addRental($rental);
        $this->getRentals()->shouldHaveCount(1);
    }

    function it_can_remove_a_rental(Rental $rental)
    {
        $this->addRental($rental);
        $this->removeRental($rental);
        $this->getRentals()->shouldHaveCount(0);
    }
    
    function it_has_no_owner_by_default()
    {
        $this->getOwner()->shouldReturn(null);
    }
    
    function its_owner_is_mutable(User $user)
    {
        $this->setOwner($user);
        $this->getOwner()->shouldReturn($user);
    }

    function it_can_cast_to_string()
    {
        $this->setCode('1000A');
        $this->__toString()->shouldBe('1000A');
    }

}
