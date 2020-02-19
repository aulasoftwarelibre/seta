<?php

namespace spec\Seta\PenaltyBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use App\Entity\Penalty;

class TimePenaltyRepositorySpec extends ObjectBehavior
{
    public function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Repository\TimePenaltyRepository');
    }

    public function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Seta\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    public function it_closes_expired_time_penalties(
        AbstractQuery $query,
        ArrayCollection $penalties,
        EntityManager $manager,
        Expr $expr,
        QueryBuilder $builder
    )
    {
        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);

        $builder->select('o')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'o', Argument::any())->shouldBeCalled()->willReturn($builder);
        $expr->eq('o.status', ':status')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $expr->lte('o.endAt', ':now')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);

        $builder->setParameter('status', Penalty::ACTIVE)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('now', new \DateTime('today'))->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn($penalties);

        $this->findExpiredPenalties()->shouldReturn($penalties);

    }
}
