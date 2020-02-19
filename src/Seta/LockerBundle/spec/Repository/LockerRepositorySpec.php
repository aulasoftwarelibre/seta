<?php

namespace spec\Seta\LockerBundle\Repository;

use App\Entity\Locker;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockerRepositorySpec extends ObjectBehavior
{
    public function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Repository\LockerRepository');
    }

    public function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Seta\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    public function it_finds_a_free_locker(
        AbstractQuery $query,
        EntityManager $manager,
        Expr $expr,
        Locker $locker,
        QueryBuilder $builder
    ) {
        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->eq('o.status', ':enabled')->shouldBeCalled()->willReturn($expr);

        $builder->select('o')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'o', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('enabled', Locker::AVAILABLE)->shouldBeCalled()->willReturn($builder);
        $builder->setMaxResults(1)->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($locker);

        $this->findOneFreeLocker();
    }
}
