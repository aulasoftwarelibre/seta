<?php

namespace spec\Seta\PenaltyBundle\Entity;

use PhpSpec\ObjectBehavior;
use App\Entity\Penalty;
use App\Entity\Rental;
use App\Entity\User;

class TimePenaltySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Entity\TimePenalty');
    }

    public function it_is_a_penalty()
    {
        $this->shouldHaveType('App\Entity\Penalty');
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
        $now = new \DateTime('now');
        $this->setEndAt($now);
        $this->getEndAt()->shouldBeLike(new \DateTime('today'));
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

    public function it_has_an_end_season_penalty_this_year()
    {
        $this->getEndSeasonPenalty(new \DateTime('august 31'))->shouldBeLike(new \DateTime('september 1 midnight'));
    }

    public function it_has_an_end_season_penalty_next_year()
    {
        $this->getEndSeasonPenalty(new \DateTime('september 1'))->shouldBeLike(new \DateTime('next year september 1 midnight'));
    }

    public function it_closes_the_penalty()
    {
        $this->close();
        $this->getStatus()->shouldBe(Penalty::DONE);
    }
}
