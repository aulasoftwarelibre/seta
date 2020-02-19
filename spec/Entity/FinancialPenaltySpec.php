<?php

namespace spec\Seta\PenaltyBundle\Entity;

use PhpSpec\ObjectBehavior;
use App\Entity\Penalty;
use App\Entity\Rental;
use App\Entity\User;

class FinancialPenaltySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Entity\FinancialPenalty');
    }

    public function it_is_a_penalty()
    {
        $this->shouldHaveType('App\Entity\Penalty');
    }

    public function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_no_default_comment()
    {
        $this->getComment()->shouldReturn(null);
    }

    public function its_comment_is_mutable()
    {
        $this->setComment('Comment');
        $this->getComment()->shouldReturn('Comment');
    }

    public function it_has_no_default_rental()
    {
        $this->getRental()->shouldReturn(null);
    }

    public function it_has_a_mutable_rental(Rental $rental)
    {
        $this->setRental($rental);
        $this->getRental()->shouldReturn($rental);
    }

    public function it_has_no_user_by_default()
    {
        $this->getUser()->shouldReturn(null);
    }

    public function it_has_a_mutable_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    public function it_has_default_status()
    {
        $this->getStatus()->shouldBe(Penalty::ACTIVE);
    }

    public function it_has_a_mutable_status()
    {
        $this->setStatus(Penalty::DONE);
        $this->getStatus()->shouldBe(Penalty::DONE);
    }

    public function it_has_no_default_amount()
    {
        $this->getAmount()->shouldBe(null);
    }

    public function it_has_a_mutable_amount()
    {
        $this->setAmount(10.1);
        $this->getAmount()->shouldBe(10.1);
    }

    public function it_is_not_paid_by_default()
    {
        $this->getIsPaid()->shouldBe(false);
    }

    public function it_has_mutable_paid()
    {
        $this->setIsPaid(true);
        $this->getIsPaid()->shouldBe(true);
    }

    public function it_closes_the_penalty()
    {
        $this->close();
        $this->getStatus()->shouldBe(Penalty::DONE);
        $this->getIsPaid()->shouldBe(true);
    }
}
