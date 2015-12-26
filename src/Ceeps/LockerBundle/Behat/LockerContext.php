<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 19:25
 */

namespace Ceeps\LockerBundle\Behat;


use AppBundle\Behat\DefaultContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Ceeps\LockerBundle\Entity\Locker;
use Ceeps\LockerBundle\Exception\NoFreeLockerException;
use Ceeps\RentalBundle\Entity\Rental;
use Ceeps\UserBundle\Entity\User;

/**
 * @codeCoverageIgnore
 */
class LockerContext extends DefaultContext
{
    /** @var  */
    private $current_user;

    private $status = [
        'disponible' => Locker::ENABLED,
        'no disponible' => Locker::DISABLED,
        'alquilada' => Locker::RENTED,
    ];

    /**
     * @When /^las siguientes taquillas:$/
     */
    public function lasSiguientesTaquillas(TableNode $table)
    {
        foreach ($table->getHash() as $item) {
            $locker = $this->getRepository('locker')->createNew();
            $locker->setCode($item['código']);
            $locker->setStatus($this->status[$item['estado']]);
            $this->getEntityManager()->persist($locker);
            $this->getEntityManager()->flush();

            if ($item['alquilada_a']) {
                $username = $item['alquilada_a'];
                $user = $this->getRepository('user')->findOneBy(['username' => $username]);
                if (!$user) {
                    throw new \Exception('User not found: ' . $username);
                }

                $this->getService('tuconsigna.rental.service')->rentLocker($user, $locker);
            }

        }
    }

    /**
     * @BeforeScenario
     */
    public function removeCurrentUser()
    {
        $this->current_user = null;
    }

    /**
     * @When /^que "([^"]*)" quiere alquilar una taquilla$/
     */
    public function queQuiereAlquilarUnaTaquilla($username)
    {
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: ' . $username);
        }
        $this->getEntityManager()->refresh($user);

        $this->current_user = $user;
    }

    /**
     * @When /^se le asigna la taquilla "([^"]*)"$/
     * @When /^se le intenta asignar la taquilla "([^"]*)"$/
     */
    public function seLeAsignaLaTaquilla($code)
    {
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }

        try {
            $this->getService('tuconsigna.rental.service')->rentLocker($this->current_user, $locker);
        } catch(\Exception $e) {
        }
    }

    /**
     * @When /^la taquilla "([^"]*)" tiene el estado "([^"]*)"$/
     * @When /^la taquilla "([^"]*)" continúa con el estado "([^"]*)"$/
     */
    public function laTaquillaTieneElEstado($code, $status)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }
        \PHPUnit_Framework_Assert::assertEquals($this->status[$status], $locker->getStatus());
    }


    /**
     * @When /^la taquilla "([^"]*)" tiene el estado "([^"]*)" por "([^"]*)"$/
     */
    public function laTaquillaTieneElEstadoPor($code, $status, $username)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: ' . $code);
        }
        \PHPUnit_Framework_Assert::assertEquals($this->status[$status], $locker->getStatus());
        \PHPUnit_Framework_Assert::assertEquals($username, $locker->getOwner()->getUsername());
    }

    /**
     * @When /^el usuario "([^"]*)" tiene (\d+) taquilla alquilada$/
     * @When /^el usuario "([^"]*)" tiene (\d+) taquillas alquiladas$/
     */
    public function elUsuarioTieneTaquillaAlquilada($username, $numLockers)
    {
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: ' . $username);
        }

        \PHPUnit_Framework_Assert::assertCount((int) $numLockers, $user->getLockers());
    }

    /**
     * @When /^se le asigna una taquilla libre$/
     */
    public function seLeAsignaUnaTaquillaLibre()
    {
        try {
            $this->getService('tuconsigna.rental.service')->rentLocker($this->current_user);
        } catch (NoFreeLockerException $e) {
        }
    }

    /**
     * @When /^que no hay taquillas libres$/
     */
    public function queNoHayTaquillasLibres()
    {
        $qb = $this->getRepository('locker')->createQueryBuilder('l');
        $qb->update('CeepsLockerBundle:Locker', 'l')
            ->set('l.status', ':disabled')
            ->setParameter('disabled', Locker::DISABLED)
            ->getQuery()->execute()
            ;
    }

    /**
     * @When /^el usuario "([^"]*)" está en la lista de espera$/
     */
    public function estáEnLaListaDeEspera($username)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: ' . $username);
        }

        \PHPUnit_Framework_Assert::assertNotEmpty($user->getQueue());
    }
}