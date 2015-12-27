<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 18:56
 */

namespace Ceeps\UserBundle\Behat;


use AppBundle\Behat\DefaultContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Ceeps\PenaltyBundle\Entity\Penalty;
use Ceeps\UserBundle\Entity\User;

/**
 * @codeCoverageIgnore
 */
class UserContext extends DefaultContext
{
    /**
     * @When /^los siguientes usuarios:$/
     */
    public function losSiguientesUsuarios(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $user = new User();
            $user->setUsername($row['email']);
            $user->setEmail($row['email']);
            $user->setPlainPassword('secret');
            if ($row['dias_sancion']) {
                $start = new \DateTime('now');
                $end = new \DateTime($row['dias_sancion'].' days 23:59:59');
                $comment = $row['comentario'];

                $this->getService('tuconsigna.service.penalty')->penalizeUser($user, $end, $comment);
            }
            $this->getEntityManager()->persist($user);
        }

        $this->getEntityManager()->flush();
    }
}