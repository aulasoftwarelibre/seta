<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 18:56
 */

namespace Seta\UserBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use Seta\CoreBundle\Behat\DefaultContext;
use Seta\PenaltyBundle\Entity\TimePenalty;
use Seta\PenaltyBundle\Event\PenaltyEvent;
use Seta\PenaltyBundle\PenaltyEvents;
use Seta\UserBundle\Entity\User;

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
            $user->setNic($this->faker->unique()->bothify("########?"));
            $user->setFullname($this->faker->name);
            if ($row['dias_sancion']) {
                $end = new \DateTime($row['dias_sancion'].' days midnight');
                $comment = $row['comentario'];

                $penalty = new TimePenalty();
                $penalty->setUser($user);
                $penalty->setEndAt($end);
                $penalty->setComment($comment);

                $this->getEntityManager()->persist($penalty);
                $this->getEntityManager()->flush();

                $this->dispatch(PenaltyEvents::PENALTY_CREATED, new PenaltyEvent($penalty));
            }
            $this->getEntityManager()->persist($user);
        }

        $this->getEntityManager()->flush();
    }
}
