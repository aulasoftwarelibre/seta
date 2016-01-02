<?php

namespace spec\Seta\RentalBundle\Entity;

use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\UserBundle\Entity\User;
use Faker\Provider\tr_TR\DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RentalSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Entity\Rental');
    }
    
    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }
    
    function it_has_start_date_by_default()
    {
        $this->getStartAt()->shouldBeAnInstanceOf(\DateTime::class);
    }
    
    function its_start_date_is_mutable()
    {
        $now = new \DateTime('now');
        $this->setStartAt($now);
        $this->getStartAt()->shouldBeLike($now);
    }

    function it_has_no_end_date_by_default()
    {
        $this->getEndAt()->shouldReturn(null);
    }

    function its_end_date_is_mutable()
    {
        $end = new \DateTime('now');
        $this->setEndAt($end);
        $this->getEndAt()->shouldBeLike(new \DateTime('today'));
    }
    
    function it_has_no_return_date_by_default()
    {
        $this->getReturnAt()->shouldReturn(null);
    }

    function its_return_date_is_mutable()
    {
        $now = new \DateTime('now');
        $this->setReturnAt($now);
        $this->getReturnAt()->shouldBeLike($now);
    }

    function it_is_renewable_by_default()
    {
        $this->getIsRenewable()->shouldReturn(true);
    }

    function its_renewable_value_is_mutable()
    {
        $this->setIsRenewable(false);
        $this->getIsRenewable()->shouldReturn(false);
    }

    function it_has_a_mutable_locker(Locker $locker)
    {
        $this->setLocker($locker);
        $this->getLocker()->shouldReturn($locker);
    }
    
    function it_has_a_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }
    
    function it_has_no_penalty_by_default()
    {
        $this->getPenalty()->shouldReturn(null);
    }
    
    function its_penalty_is_mutable(Penalty $penalty)
    {
        $this->setPenalty($penalty);
        $this->getPenalty()->shouldReturn($penalty);
    }

    function it_get_days_left()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLeft()->shouldReturn(5);
    }
    
    function it_get_zero_days_left()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);
        
        $this->getDaysLeft()->shouldReturn(0);
    }

    function it_get_days_late()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLate()->shouldReturn(5);
    }

    function it_get_zero_days_late()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getDaysLate()->shouldReturn(0);
    }

    function it_get_is_not_expired()
    {
        $end = new \DateTime('5 days midnight');
        $this->setEndAt($end);

        $this->getIsExpired()->shouldBe(false);
    }

    function it_get_is_expired()
    {
        $end = new \DateTime('-5 days midnight');
        $this->setEndAt($end);

        $this->getIsExpired()->shouldBe(true);
    }

    function it_has_default_renew_code()
    {
        $this->getRenewCode()->shouldBeString();
    }
    
    function its_renew_code_is_mutable()
    {
        $this->setRenewCode("CODE");
        $this->getRenewCode()->shouldBeEqualTo("CODE");
    }
}
