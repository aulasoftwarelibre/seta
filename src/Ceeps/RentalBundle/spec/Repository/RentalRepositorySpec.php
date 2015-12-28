<?php

namespace spec\Ceeps\RentalBundle\Repository;

use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\UserBundle\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RentalRepositorySpec extends ObjectBehavior
{
    function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ceeps\RentalBundle\Repository\RentalRepository');
    }

    function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Ceeps\ResourceBundle\Doctrine\ORM\EntityRepository');
    }

    function it_finds_current_locker_rental(
        Locker $locker,
        Rental $rental,
        User $user,
        EntityManager $manager,
        QueryBuilder $builder,
        AbstractQuery $query,
        Expr $expr
    )
    {
        $locker->getOwner()->shouldBeCalled()->willReturn($user);

        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);

        $builder->select('o')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'o', Argument::any())->shouldBeCalled()->willReturn($builder);
        $expr->eq('o.user', ':user')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $expr->eq('o.locker', ':locker')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $expr->isNull('o.returnAt')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);

        $builder->setParameter('user', $user)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('locker', $locker)->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled()->willReturn($rental);

        $this->getCurrentRental($locker);
    }


}
