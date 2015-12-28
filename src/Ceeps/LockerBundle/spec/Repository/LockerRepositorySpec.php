<?php

namespace spec\Ceeps\LockerBundle\Repository;

use Ceeps\LockerBundle\Entity\Locker;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockerRepositorySpec extends ObjectBehavior
{
    function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\LockerBundle\Repository\LockerRepository');
    }

    function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Ceeps\ResourceBundle\Doctrine\ORM\EntityRepository');
    }
    
    function it_finds_a_free_locker(
        AbstractQuery $query,
        EntityManager $manager,
        Expr $expr,
        Locker $locker,
        QueryBuilder $builder
    )
    {
        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->eq('l.status', ':enabled')->shouldBeCalled()->willReturn($expr);

        $builder->select('l')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'l', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('enabled', Locker::AVAILABLE)->shouldBeCalled()->willReturn($builder);
        $builder->setMaxResults(1)->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($locker);

        $this->findOneFreeLocker();
    }
}
