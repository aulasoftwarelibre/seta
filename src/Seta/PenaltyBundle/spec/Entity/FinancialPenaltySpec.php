<?php

namespace spec\Seta\PenaltyBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\PenaltyBundle\Entity\Penalty;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;

class FinancialPenaltySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Entity\FinancialPenalty');
    }
    
    function it_is_a_penalty()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Entity\Penalty');
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_default_comment()
    {
        $this->getComment()->shouldReturn(null);
    }

    function its_comment_is_mutable()
    {
        $this->setComment("Comment");
        $this->getComment()->shouldReturn("Comment");
    }

    function it_has_no_default_rental()
    {
        $this->getRental()->shouldReturn(null);
    }

    function it_has_a_mutable_rental(Rental $rental)
    {
        $this->setRental($rental);
        $this->getRental()->shouldReturn($rental);
    }

    function it_has_no_user_by_default()
    {
        $this->getUser()->shouldReturn(null);
    }

    function it_has_a_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_has_default_status()
    {
        $this->getStatus()->shouldBe(Penalty::ACTIVE);
    }

    function it_has_a_mutable_status()
    {
        $this->setStatus(Penalty::DONE);
        $this->getStatus()->shouldBe(Penalty::DONE);
    }

    function it_has_no_default_ammount()
    {
        $this->getAmmount()->shouldBe(null);
    }

    function it_has_a_mutable_ammount()
    {
        $this->setAmmount(10.1);
        $this->getAmmount()->shouldBe(10.1);
    }
    
    function it_is_not_paid_by_default()
    {
        $this->getIsPaid()->shouldBe(false);
    }
    
    function it_has_mutable_paid()
    {
        $this->setIsPaid(true);
        $this->getIsPaid()->shouldBe(true);
    }
}
