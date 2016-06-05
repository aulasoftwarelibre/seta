<?php
/*
 * This file is part of the nodos.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Uco\Bundle\UserBundle\Security\User;

use FOS\UserBundle\Security\UserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Uco\Bundle\UserBundle\Entity\User;

class UcoUserProvider implements UserProviderInterface
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * Constructor.
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf(
                'Instancia de la clases "%s" no están soportadas.', get_class($user)
            ));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
