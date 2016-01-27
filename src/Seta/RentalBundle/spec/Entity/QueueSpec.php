<?php

namespace spec\Seta\RentalBundle\Entity;

use Seta\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;

class QueueSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Entity\Queue');
    }

    public function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_create_date_by_default()
    {
        $this->getCreatedAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    public function its_create_date_is_mutable()
    {
        $now = new \DateTime('tomorrow');
        $this->setCreatedAt($now);
        $this->getCreatedAt()->shouldBeLike($now);
    }

    public function it_has_no_user_by_default()
    {
        $this->getUser()->shouldReturn(null);
    }

    public function its_user_is_mutable(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }
}
