<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 15:06
 */

namespace Ceeps\RentalBundle\Behat;


use AppBundle\Behat\DefaultContext;
use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\NotFreeLockerException;

/**
 * Class RentalContext
 * @package Ceeps\RentalBundle\Behat
 * @codeCoverageIgnore
 */
class RentalContext extends DefaultContext
{
    /**
     * @When /^la taquilla "([^"]*)" es devuelta$/
     */
    public function laTaquillaEsDevuelta($code)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }
        $this->getService('tuconsigna.rental.service')->returnLocker($locker);
    }
}