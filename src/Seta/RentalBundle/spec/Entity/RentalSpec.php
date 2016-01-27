<?php

namespace spec\Seta\RentalBundle\Entity;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;

class RentalSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Entity\Rental');
    }

    public function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_start_date_by_default()
    {
        $this->getStartAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    public function its_start_date_is_mutable()
    {
        $now = new \DateTime('now');
        $this->setStartAt($now);
        $this->getStartAt()->shouldBeLike($now);
    }

    public function it_has_no_end_date_by_default()
    {
        $this->getEndAt()->shouldReturn(null);
    }

    public function its_end_date_is_mutable()
    {
        $end = new \DateTime('now');
        $this->setEndAt($end);
        $this->getEndAt()->shouldBeLike(new \DateTime('today'));
    }

    public function it_has_no_return_date_by_default()
    {
        $this->getReturnAt()->shouldReturn(null);
    }

    public function its_return_date_is_mutable()
    {
        $now = new \DateTime('now');
        $this->setReturnAt($now);
        $this->getReturnAt()->shouldBeLike($now);
    }

    public function it_is_renewable_by_default()
    {
        $this->getIsRenewable()->shouldReturn(true);
    }

    public function its_renewable_value_is_mutable()
    {
        $this->setIsRenewable(false);
        $this->getIsRenewable()->shouldReturn(false);
    }

    public function it_has_a_mutable_locker(Locker $locker)
    {
        $this->setLocker($locker);
        $this->getLocker()->shouldReturn($locker);
    }

    public function it_has_a_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    public function it_has_no_penalty_by_default()
    {
        $this->getPenalty()->shouldReturn(null);
    }

    public function its_penalty_is_mutable(Penalty $penalty)
    {
        $this->setPenalty($penalty);
        $this->getPenalty()->shouldReturn($penalty);
    }

    public function it_get_days_left()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLeft()->shouldReturn(5);
    }

    public function it_get_zero_days_left()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLeft()->shouldReturn(0);
    }

    public function it_get_days_late()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLate()->shouldReturn(5);
    }

    public function it_get_zero_days_late()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLate()->shouldReturn(0);
    }

    public function it_get_is_not_expired()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getIsExpired()->shouldBe(false);
    }

    public function it_get_is_expired()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);

        $this->getIsExpired()->shouldBe(true);
    }

    public function it_has_default_renew_code()
    {
        $this->getRenewCode()->shouldBeString();
    }

    public function its_renew_code_is_mutable()
    {
        $this->setRenewCode('CODE');
        $this->getRenewCode()->shouldBeEqualTo('CODE');
    }
}
