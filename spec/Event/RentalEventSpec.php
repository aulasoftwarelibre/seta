<?php

namespace spec\Seta\RentalBundle\Event;

use PhpSpec\ObjectBehavior;
use App\Entity\Rental;

class RentalEventSpec extends ObjectBehavior
{
    public function let(Rental $rental)
    {
        $this->beConstructedWith($rental);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Event\RentalEvent');
    }

    public function it_has_a_default_rental(Rental $rental)
    {
        $this->getRental()->shouldBe($rental);
    }
}
