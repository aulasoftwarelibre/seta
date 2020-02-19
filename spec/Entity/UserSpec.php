<?php

namespace spec\Seta\UserBundle\Entity;

use App\Entity\Locker;
use App\Entity\Penalty;
use App\Entity\Queue;
use App\Entity\Rental;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Entity\User');
    }

    public function it_should_extends_fos_user_model()
    {
        $this->shouldHaveType('FOS\UserBundle\Model\User');
    }

    public function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_is_no_penalized_by_default()
    {
        $this->getIsPenalized()->shouldReturn(false);
    }

    public function it_can_be_penalized()
    {
        $this->setIsPenalized(true);
        $this->getIsPenalized()->shouldReturn(true);
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

    public function it_has_no_penalties_by_default()
    {
        $this->getPenalties()->shouldHaveCount(0);
    }

    public function it_can_add_a_penalty(Penalty $penalty)
    {
        $this->addPenalty($penalty);
        $this->getPenalties()->shouldHaveCount(1);
    }

    public function it_can_remove_a_penalty(Penalty $penalty)
    {
        $this->addPenalty($penalty);
        $this->removePenalty($penalty);
        $this->getPenalties()->shouldHaveCount(0);
    }

    public function it_has_no_locker_by_default()
    {
        $this->getLocker()->shouldBe(null);
    }

    public function it_can_set_a_locker(Locker $locker)
    {
        $this->setLocker($locker);
        $this->getLocker()->shouldBe($locker);
    }

    public function it_has_no_queue_by_default()
    {
        $this->getQueue()->shouldReturn(null);
    }

    public function its_queue_is_mutable(Queue $queue)
    {
        $this->setQueue($queue);
        $this->getQueue()->shouldReturn($queue);
    }

    public function it_has_no_default_nic()
    {
        $this->getNic()->shouldReturn(null);
    }

    public function its_nic_is_mutable()
    {
        $this->setNic('12345678A');
        $this->getNic()->shouldReturn('12345678A');
    }

    public function it_has_no_fullname_by_default()
    {
        $this->getFullname()->shouldBe(null);
    }

    public function its_fullname_is_mutable()
    {
        $this->setFullname('John Doe');
        $this->getFullname()->shouldBe('John Doe');
    }
}
