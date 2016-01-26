<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 01:45
 */

namespace Seta\PenaltyBundle\Behat;


use Seta\CoreBundle\Behat\DefaultContext;
use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\UserBundle\Entity\User;

/**
 * Class PenaltyContext
 * @package Seta\PenaltyBundle\Behat
 * @codeCoverageIgnore
 */
class PenaltyContext extends DefaultContext
{

    /**
     * @When /^el usuario "([^"]*)" no tiene sanciones$/
     */
    public function elUsuarioNoTieneSanciones($username)
    {
        /** @var User $user */
        $user = $this->getRepository('user')->findOneBy(['username' => $username]);
        if (!$user) {
            throw new \Exception('User not found: ' . $username);
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
            throw new \Exception('User not found: ' . $username);
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
            throw new \Exception('User not found: ' . $username);
        }
        \PHPUnit_Framework_Assert::assertTrue($user->getIsPenalized());

        $this->getEntityManager()->refresh($user);

        /** @var TimePenalty $penalty */
        $penalty = $user->getPenalties()->current();
        $endSeason = TimePenalty::getEndSeasonPenalty();

        \PHPUnit_Framework_Assert::assertGreaterThanOrEqual($endSeason, $penalty->getEndAt());
    }
}
