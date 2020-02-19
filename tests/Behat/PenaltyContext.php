<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 01:45.
 */
namespace App\Tests\Behat;

use Behat\Gherkin\Node\TableNode;
use Seta\CoreBundle\Behat\DefaultContext;
use App\Entity\Locker;
use App\Entity\FinancialPenalty;
use App\Entity\TimePenalty;
use App\Event\PenaltyEvent;
use App\Events\PenaltyEvents;
use App\Entity\User;

/**
 * Class PenaltyContext.
 *
 * @codeCoverageIgnore
 */
class PenaltyContext extends DefaultContext
{
    private $amount;

    /**
     * @When /^el usuario "([^"]*)" no tiene sanciones$/
     */
    public function elUsuarioNoTieneSanciones($username)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: '.$username);
        }

        \PHPUnit_Framework_Assert::assertFalse($user->getIsPenalized());
    }

    /**
     * @When /^el usuario "([^"]*)" tiene una sanción por la taquilla "([^"]*)" de (\d+) días$/
     */
    public function elUsuarioTieneUnaSanciónPorLaTaquillaDeDías($username, $code, $days)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: '.$username);
        }
        \PHPUnit_Framework_Assert::assertTrue($user->getIsPenalized());

        $this->getEntityManager()->refresh($user);

        /** @var TimePenalty $penalty */
        $penalty = $user->getPenalties()->current();
        $diff = $penalty->getEndAt()->diff(new \DateTime('today'))->days;

        \PHPUnit_Framework_Assert::assertEquals($days, $diff);
    }

    /**
     * @When /^el usuario "([^"]*)" tiene una sanción por la taquilla "([^"]*)" de todo el curso$/
     */
    public function elUsuarioTieneUnaSanciónPorLaTaquillaDeTodoElCurso($username, $code)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: '.$username);
        }
        \PHPUnit_Framework_Assert::assertTrue($user->getIsPenalized());

        $this->getEntityManager()->refresh($user);

        $penalties = $user->getPenalties();
        $endSeason = TimePenalty::getEndSeasonPenalty();

        foreach ($penalties as $penalty) {
            if ($penalty instanceof TimePenalty) {
                if ($penalty->getRental()->getLocker()->getCode() == $code) {
                    \PHPUnit_Framework_Assert::assertGreaterThanOrEqual($endSeason, $penalty->getEndAt());

                    return true;
                }
            }
        }

        throw new \Exception('TimePenalty not found.');
    }

    /**
     * @When /^el usuario "([^"]*)" tiene una sanción económica por la taquilla "([^"]*)"$/
     */
    public function elUsuarioTieneUnaSanciónEconómicaPorLaTaquilla($username, $code)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: '.$username);
        }
        \PHPUnit_Framework_Assert::assertTrue($user->getIsPenalized());

        $this->getEntityManager()->refresh($user);

        $penalties = $user->getPenalties();

        foreach ($penalties as $penalty) {
            if ($penalty instanceof FinancialPenalty) {
                if ($penalty->getRental()->getLocker()->getCode() == $code) {
                    \PHPUnit_Framework_Assert::assertEquals($this->amount, $penalty->getAmount());

                    return true;
                }
            }
        }

        throw new \Exception('FinancialPenalty not found.');
    }

    /**
     * @When /^la taquilla "([^"]*)" no es devuelta y se rompe el candado$/
     */
    public function laTaquillaNoEsDevueltaYSeRompeElCandado($code)
    {
        /** @var Locker $locker */
        $locker = $this->getRepository('locker')->findOneBy(['code' => $code]);
        if (!$locker) {
            throw new \Exception('Locker not found: '.$code);
        }

        $rental = $this->getRepository('rental')->getCurrentRental($locker);

        $this->getContainer()->get('seta.service.return')->returnRental($rental);
        $this->getContainer()->get('seta.service.financial_penalty')->penalizeRental($rental, $this->amount);
    }

    /**
     * @When /^la sanción por no devolver el candado de (\d+) euros$/
     */
    public function laSanciónPorNoDevolverElCandadoDeEuros($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @When /^las siguientes sanciones de tiempo:$/
     */
    public function lasSiguientesSanciones(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $user = $this->getEntityManager()->getRepository('SetaUserBundle:User')->findOneBy(['email' => $row['usuario']]);
            if (!$user) {
                throw new \Exception('Usuario no encontrado: '.$row['usuario']);
            }

            $end = new \DateTime($row['dias_sancion'].' days midnight');
            $comment = 'Sanción automática';

            $penalty = new TimePenalty();
            $penalty->setUser($user);
            $penalty->setEndAt($end);
            $penalty->setComment($comment);

            $this->getEntityManager()->persist($penalty);

            $this->dispatch(PenaltyEvents::PENALTY_CREATED, new PenaltyEvent($penalty));
        }

        $this->getEntityManager()->flush();
    }
}
