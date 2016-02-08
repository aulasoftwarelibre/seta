<?php

namespace spec\Seta\PenaltyBundle\Business;

use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Entity\FinancialPenalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\PenaltyBundle\Repository\FinancialPenaltyRepository;
use Seta\RentalBundle\Entity\Rental;
use Seta\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FinancialPenaltyServiceSpec extends ObjectBehavior
{
    private $amount = 2.0;

    public function let(
        EventDispatcherInterface $dispatcher,
        ObjectManager $manager,
        FinancialPenaltyRepository $penaltyRepository,
        Locker $locker,
        Rental $rental,
        User $user
    ) {
        $this->beConstructedWith($manager, $penaltyRepository, $dispatcher, $this->amount);

        $locker->getCode()->willReturn('100');

        $rental->getUser()->willReturn($user);
        $rental->getLocker()->willReturn($locker);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Seta\PenaltyBundle\Business\FinancialPenaltyService');
    }

    public function it_can_add_a_penalty_from_a_rent(
        EventDispatcherInterface $dispatcher,
        ObjectManager $manager,
        Locker $locker,
        FinancialPenalty $penalty,
        FinancialPenaltyRepository $penaltyRepository,
        Rental $rental,
        User $user
    ) {
        $rental->getLocker()->shouldBeCalled();
        $locker->getCode()->shouldBeCalled();
        $comment = 'Penalización automática por retraso al entregar la taquilla 100';

        $rental->getUser()->shouldBeCalled();

        $penaltyRepository->createNew()->shouldBeCalled()->willReturn($penalty);
        $penalty->setUser($user)->shouldBeCalled();
        $penalty->setAmount($this->amount)->shouldBeCalled();
        $penalty->setComment($comment)->shouldBeCalled();
        $penalty->setRental($rental)->shouldBeCalled();

        $manager->persist($penalty)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $dispatcher->dispatch(PenaltyEvents::PENALTY_CREATED, Argument::type(PenaltyEvent::class))->shouldBeCalled();

        $this->penalizeRental($rental);
    }
}
