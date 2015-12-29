<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 19:42
 */

namespace Ceeps\RentalBundle\Behat;


use Ceeps\CoreBundle\Behat\DefaultContext;
use Doctrine\Common\Collections\ArrayCollection;

class SearchRentalContext extends DefaultContext
{
    /**
     * @var ArrayCollection
     */
    private $rentals;

    /**
     * @beforeScenario
     */
    public function beforeScenario()
    {
        $this->rentals = [];
    }

    /**
     * @When /^buscamos los alquileres que van a caducar dentro de (.*) días$/
     */
    public function buscamosLosAlquileresQueVanACaducarDentroDeDías($days)
    {
        $on = new \DateTime($days.' days');

        $this->rentals = $this->getRepository('rental')->getExpireOnDateRentals($on);
    }

    /**
     * @When /^encontramos (.*) alquileres$/
     */
    public function encontramosAlquileres($total)
    {
        \PHPUnit_Framework_Assert::assertEquals($total, count($this->rentals));
    }


}