<?php

namespace spec\Ceeps\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityRepositorySpec extends ObjectBehavior
{
    function let(EntityManagerInterface $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
        $metadata->name = 'DateTime';
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    function it_create_a_new_resource(EntityManagerInterface $manager)
    {
        $this->createNew()->shouldBeLike(new \DateTime());
    }
}
