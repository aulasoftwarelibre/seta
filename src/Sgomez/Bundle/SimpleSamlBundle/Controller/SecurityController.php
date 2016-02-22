<?php

/*
 * This file is part of the SimpleSamlBundle.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\Bundle\SimpleSamlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function connectAction(Request $request)
    {
        $auth = $this->get('sgomez_simplesaml.auth')->getAuth();

        $returnTo = $this->generateUrl('simplesaml_security_check');
        $url = $auth->getLoginURL($returnTo);

        return $this->redirect($url);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
