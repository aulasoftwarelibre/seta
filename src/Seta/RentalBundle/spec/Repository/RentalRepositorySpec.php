<?php

namespace spec\Seta\RentalBundle\Repository;

use Seta\LockerBundle\Entity\Locker;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
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
        $this->shouldHaveType('Seta\RentalBundle\Repository\RentalRepository');
    }

    function it_should_extend_from_repository_class()
    {
        $this->shouldHaveType('Seta\ResourceBundle\Doctrine\ORM\EntityRepository');
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

    function it_finds_expire_on_rentals(
        ArrayCollection $rentals,
        EntityManager $manager,
        QueryBuilder $builder,
        AbstractQuery $query,
        Expr $expr
    )
    {
        $on = new \DateTime('now');

        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);

        $builder->select('o')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'o', Argument::any())->shouldBeCalled()->willReturn($builder);
        $expr->between('o.getEnd', ':start', ':end')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $expr->isNull('o.returnAt')->shouldBeCalled()->willReturn($expr);
        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);

        $builder->setParameter('start', Argument::type(\DateTime::class))->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('end', Argument::type(\DateTime::class))->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn($rentals);

        $this->getExpireOnDateRentals($on);
    }

}
