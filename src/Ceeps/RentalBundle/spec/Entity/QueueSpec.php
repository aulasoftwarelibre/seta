<?php

namespace spec\Ceeps\RentalBundle\Entity;

use Ceeps\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\RentalBundle\Entity\Queue');
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_create_date_by_default()
    {
        $this->getCreatedAt()->shouldBeAnInstanceOf(\DateTime::class);
    }

    function its_create_date_is_mutable()
    {
        $now = new \DateTime('tomorrow');
        $this->setCreatedAt($now);
        $this->getCreatedAt()->shouldBeLike($now);
    }
    
    function it_has_no_user_by_default()
    {
        $this->getUser()->shouldReturn(null);
    }
    
    function its_user_is_mutable(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

}
