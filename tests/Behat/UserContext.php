<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/12/15
 * Time: 18:56.
 */
namespace App\Tests\Behat;

use Behat\Gherkin\Node\TableNode;
use Seta\CoreBundle\Behat\DefaultContext;
use App\Entity\Faculty;
use App\Entity\User;

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
            $user->setNic($this->faker->unique()->bothify('########?'));
            $user->setFullname($this->faker->name);
            $user->setPhonenumber($this->faker->phoneNumber);

            $faculty = $this->getEntityManager()->getRepository('SetaUserBundle:Faculty')->findOneBy(['slug' => $row['centro']]);
            if (!$faculty) {
                throw new \Exception('Facultad no encontrada: '.$row['centro']);
            }
            $user->setFaculty($faculty);

            $this->getEntityManager()->persist($user);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @When /^los siguientes centros:$/
     */
    public function losSiguientesCentros(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $faculty = new Faculty();
            $faculty->setName($row['nombre']);
            $faculty->setSlug($row['código']);
            $faculty->setIsEnabled($row['activo'] === 'sí');

            $this->getEntityManager()->persist($faculty);
        }

        $this->getEntityManager()->flush();
    }
}
