<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 15:06.
 */
namespace Seta\RentalBundle\Behat;

use Seta\CoreBundle\Behat\DefaultContext;
use Seta\LockerBundle\Entity\Locker;
use Seta\PenaltyBundle\Exception\PenalizedFacultyException;
use Seta\PenaltyBundle\Exception\PenalizedUserException;
use Seta\RentalBundle\Entity\Rental;
use Seta\RentalBundle\Exception\ExpiredRentalException;
use Seta\RentalBundle\Exception\NotRenewableRentalException;
use Seta\RentalBundle\Exception\TooEarlyRenovationException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class RentalContext.
 *
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
            throw new \Exception('Locker not found: '.$code);
        }

        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        $this->getContainer()->get('seta.service.return')->returnRental($rental);
    }

    /**
     * @When /^se quiere renovar el alquiler de la taquilla "([^"]*)"$/
     */
    public function seQuiereRenovarElAlquilerDeLaTaquilla($code)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: '.$code);
        }

        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        try {
            $this->getContainer()->get('seta.service.renew')->renewRental($rental);
        } catch (NotRenewableRentalException $e) {
        } catch (ExpiredRentalException $e) {
        } catch (TooEarlyRenovationException $e) {
        } catch (PenalizedFacultyException $e) {
        } catch (PenalizedUserException $e) {
        } catch (Exception $e) {
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
            throw new \Exception('Locker not found: '.$code);
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
            throw new \Exception('Locker not found: '.$code);
        }
        /** @var Rental $rental */
        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        $now = new \DateTime('now');

        \PHPUnit_Framework_Assert::assertLessThan($now, $rental->getEndAt());
    }
}
