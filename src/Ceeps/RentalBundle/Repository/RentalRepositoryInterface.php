<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 04:12
 */

namespace Ceeps\RentalBundle\Repository;


use Ceeps\LockerBundle\Entity\Locker;

interface RentalRepositoryInterface
{
    public function getCurrentRental(Locker $locker);
}