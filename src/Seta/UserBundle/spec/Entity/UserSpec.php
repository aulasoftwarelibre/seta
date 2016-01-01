<?php

namespace spec\Seta\UserBundle\Entity;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\RentalBundle\Entity\Queue;
use Seta\RentalBundle\Entity\Rental;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\UserBundle\Entity\User');
    }

    function it_should_extends_fos_user_model()
    {
        $this->shouldHaveType('FOS\UserBundle\Model\User');
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }
    
    function it_is_no_penalized_by_default()
    {
        $this->getIsPenalized()->shouldReturn(false);
    }
    
    function it_can_be_penalized()
    {
        $this->setIsPenalized(true);
        $this->getIsPenalized()->shouldReturn(true);
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

    function it_has_no_penalties_by_default()
    {
        $this->getPenalties()->shouldHaveCount(0);
    }

    function it_can_add_a_penalty(Penalty $penalty)
    {
        $this->addPenalty($penalty);
        $this->getPenalties()->shouldHaveCount(1);
    }

    function it_can_remove_a_penalty(Penalty $penalty)
    {
        $this->addPenalty($penalty);
        $this->removePenalty($penalty);
        $this->getPenalties()->shouldHaveCount(0);
    }

    function it_has_no_lockers_by_default()
    {
        $this->getLockers()->shouldHaveCount(0);
    }

    function it_can_add_a_locker(Locker $locker)
    {
        $this->addLocker($locker);
        $this->getLockers()->shouldHaveCount(1);
    }

    function it_can_remove_a_locker(Locker $locker)
    {
        $this->addLocker($locker);
        $this->removeLocker($locker);
        $this->getLockers()->shouldHaveCount(0);
    }
    
    function it_has_no_queue_by_default()
    {
        $this->getQueue()->shouldReturn(null);        
    }
    
    function its_queue_is_mutable(Queue $queue)
    {
        $this->setQueue($queue);
        $this->getQueue()->shouldReturn($queue);
    }
    
    function it_has_no_default_nic()
    {
        $this->getNic()->shouldReturn(null);
    }

    function its_nic_is_mutable()
    {
        $this->setNic("12345678A");
        $this->getNic()->shouldReturn("12345678A");
    }
    
    function it_has_no_fullname_by_default()
    {
        $this->getFullname()->shouldBe(null);
    }
    
    function its_fullname_is_mutable()
    {
        $this->setFullname('John Doe');
        $this->getFullname()->shouldBe('John Doe');
    }
}
