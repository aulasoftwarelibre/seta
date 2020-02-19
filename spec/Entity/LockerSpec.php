<?php

namespace spec\Seta\LockerBundle\Entity;

use App\Entity\Locker;
use App\Entity\Queue;
use App\Entity\Rental;
use App\Entity\User;
use PhpSpec\ObjectBehavior;

class LockerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Entity\Locker');
    }

    public function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_no_code_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function its_code_is_mutable()
    {
        $this->setCode('1000A');
        $this->getCode()->shouldReturn('1000A');
    }

    public function it_has_enabled_status_by_default()
    {
        $this->getStatus()->shouldReturn(Locker::AVAILABLE);
    }

    public function its_status_is_mutable()
    {
        $this->setStatus(Locker::UNAVAILABLE);
        $this->getStatus()->shouldReturn(Locker::UNAVAILABLE);
    }

    public function it_has_no_rentals_by_default()
    {
        $this->getRentals()->shouldHaveCount(0);
    }

    public function it_can_add_a_rental(Rental $rental)
    {
        $this->addRental($rental);
        $this->getRentals()->shouldHaveCount(1);
    }

    public function it_can_remove_a_rental(Rental $rental)
    {
        $this->addRental($rental);
        $this->removeRental($rental);
        $this->getRentals()->shouldHaveCount(0);
    }

    public function it_has_no_owner_by_default()
    {
        $this->getOwner()->shouldReturn(null);
    }

    public function its_owner_is_mutable(User $user)
    {
        $this->setOwner($user);
        $this->getOwner()->shouldReturn($user);
    }

    public function it_can_cast_to_string()
    {
        $this->setCode('1000A');
        $this->__toString()->shouldBe('1000A');
    }

    public function it_has_no_default_queue()
    {
        $this->getQueue()->shouldBe(null);
    }

    public function its_queue_is_mutable(Queue $queue)
    {
        $this->setQueue($queue);
        $this->getQueue()->shouldBe($queue);
    }
}
