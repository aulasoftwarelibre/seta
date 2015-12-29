<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 15:06
 */

namespace Ceeps\RentalBundle\Behat;


use Ceeps\CoreBundle\Behat\DefaultContext;
use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\RentalBundle\Exception\ExpiredRentalException;
use Ceeps\RentalBundle\Exception\NotRenewableRentalException;
use Ceeps\RentalBundle\Exception\TooEarlyRenovationException;
use Symfony\Component\Config\Definition\Exception\Exception;

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
        $this->getContainer()->get('tuconsigna.service.return')->returnLocker($locker);
    }

    /**
     * @When /^se quiere renovar el alquiler de la taquilla "([^"]*)"$/
     */
    public function seQuiereRenovarElAlquilerDeLaTaquilla($code)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }

        try {
            $this->getContainer()->get('tuconsigna.service.renew')->renewLocker($locker);
        } catch(NotRenewableRentalException $e) {
        } catch(ExpiredRentalException $e) {
        } catch(TooEarlyRenovationException $e) {
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * @When /^el alquiler de la taquilla "([^"]*)" caducará dentro de (\d+) días$/
     */
    public function elAlquilerDeLaTaquillaCaducaráDentroDeDías($code, $days)
    {
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }
        /** @var Rental $rental */
        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        \PHPUnit_Framework_Assert::assertEquals($days, $rental->getDaysLeft());
    }

    /**
     * @When /^el alquiler de la taquilla "([^"]*)" ha caducado$/
     */
    public function elAlquilerDeLaTaquillaHaCaducado($code)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }
        /** @var Rental $rental */
        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        $now = new \DateTime('now');

        \PHPUnit_Framework_Assert::assertLessThan($now, $rental->getEndAt());
    }
}