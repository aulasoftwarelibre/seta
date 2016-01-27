<?php

namespace spec\Seta\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;

class EntityRepositorySpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
        $metadata->name = 'DateTime';
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    public function it_create_a_new_resource(EntityManagerInterface $manager)
    {
        $this->createNew()->shouldBeLike(new \DateTime());
    }
}
