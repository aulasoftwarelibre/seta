<?php

namespace spec\Seta\RentalBundle\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Seta\RentalBundle\Entity\Rental;

class RentalEventSpec extends ObjectBehavior
{
    function let(Rental $rental)
    {
        $this->beConstructedWith($rental);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Seta\RentalBundle\Event\RentalEvent');
    }
    
    function it_has_a_default_rental(Rental $rental)
    {
        $this->getRental()->shouldBe($rental);
    }
}
