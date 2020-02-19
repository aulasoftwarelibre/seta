<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Security\User;


use FOS\UserBundle\Security\UserProvider;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SetaUserProvider implements UserProviderInterface
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * SetaUserProvider constructor.
     *
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }


    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf(
                'El usuario "%s" no existe.', $username
            ));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf(
                'Instancia de la clases "%s" no están soportadas.', get_class($user)
            ));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
