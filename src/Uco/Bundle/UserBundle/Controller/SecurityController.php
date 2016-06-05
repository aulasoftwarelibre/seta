<?php
/*
 * This file is part of the nodos.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Uco\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $exception = $this->get('security.authentication_utils')->getLastAuthenticationError();

        return $this->render('@UcoUser/security/login.html.twig', [
            'error' => $exception,
        ]);
    }

    public function logoutAction(Request $request)
    {
        throw new \RuntimeException('La ruta /logout debe estar activa en el cortafuegos.');
    }
}
