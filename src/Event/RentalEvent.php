<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 01/01/16
 * Time: 21:33.
 */
namespace App\Event;

use App\Entity\Rental;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RentalEvent.
 */
class RentalEvent extends Event
{
    /**
     * @var Rental
     */
    private $rental;

    /**
     * RentalEvent constructor.
     *
     * @param Rental $rental
     */
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    /**
     * @return Rental
     */
    public function getRental()
    {
        return $this->rental;
    }
}
