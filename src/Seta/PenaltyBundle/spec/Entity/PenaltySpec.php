<?php

namespace spec\Seta\PenaltyBundle\Entity;

use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PenaltySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Entity\Penalty');
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
        $now = new \DateTime('now');
        $this->setEndAt($now);
        $this->getEndAt()->shouldBeLike($now);
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
}
