<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 11:28
 */

namespace Ceeps\PenaltyBundle\Business;


use Ceeps\RentalBundle\Entity\Rental;

interface PenaltyServiceInterface
{
    public function penalizeRental(Rental $rental);
}