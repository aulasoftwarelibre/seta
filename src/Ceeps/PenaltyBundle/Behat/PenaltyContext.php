<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 01:45
 */

namespace Ceeps\PenaltyBundle\Behat;


use AppBundle\Behat\DefaultContext;
use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\UserBundle\Entity\User;

/**
 * Class PenaltyContext
 * @package Ceeps\PenaltyBundle\Behat
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

        /** @var Penalty $penalty */
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

        /** @var Penalty $penalty */
        $penalty = $user->getPenalties()->current();
        $nextCourse = new \DateTime('next year august 31');

        \PHPUnit_Framework_Assert::assertGreaterThanOrEqual($nextCourse, $penalty->getEndAt());
    }
}