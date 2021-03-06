<?php

namespace spec\Seta\UserBundle\Entity;

use PhpSpec\ObjectBehavior;
use Seta\UserBundle\Entity\User;

class GroupSpec extends ObjectBehavior
{
    const GROUP_NAME = 'Users';
    const GROUP_ROLES = ['ROLE_USER'];

    public function let()
    {
        $this->beConstructedWith(self::GROUP_NAME, self::GROUP_ROLES);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\UserBundle\Entity\Group');
    }

    public function it_has_no_default_users()
    {
        $this->getUsers()->shouldHaveCount(0);
    }

    public function it_can_add_a_user(User $user)
    {
        $this->addUser($user);
        $this->getUsers()->shouldHaveCount(1);
    }

    public function it_can_remove_a_user(User $user)
    {
        $this->addUser($user);
        $this->removeUser($user);
        $this->getUsers()->shouldHaveCount(0);
    }

    public function it_has_a_string_casting()
    {
        $this->__toString()->shouldBe(self::GROUP_NAME);
    }
}
