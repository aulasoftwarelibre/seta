<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:28
 */

namespace Seta\PenaltyBundle\Business;


use Seta\RentalBundle\Entity\Rental;

interface PenaltyServiceInterface
{
    public function penalizeRental(Rental $rental);
}
