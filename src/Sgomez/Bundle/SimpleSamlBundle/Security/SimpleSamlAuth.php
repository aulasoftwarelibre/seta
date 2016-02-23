<?php
/*
 * This file is part of the SimpleSamlBundle.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sgomez\Bundle\SimpleSamlBundle\Security;

use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class SimpleSamlAuth
{
    /**
     * @var \SimpleSAML_Auth_Simple
     */
    private $auth;
    private $userIdAttribute;

    public function __construct($authSource, $userIdAttribute)
    {
        $this->auth = new \SimpleSAML_Auth_Simple($authSource);
        $this->userIdAttribute = $userIdAttribute;
    }

    public function isAuthenticated()
    {
        return $this->auth->isAuthenticated();
    }

    public function getAttributes()
    {
        return $this->auth->getAttributes();
    }

    public function getCredentials()
    {
        $credentials = [];

        foreach ($this->auth->getAttributes() as $key => $value) {
            if (1 === count($value)) {
                $credentials[$key] = $value;
            } else {
                $credentials[$key] = $value[0];
            }
        }

        $credentials['username'] = $this->getUsername();

        return $credentials;
    }

    public function getLoginUrl($returnTo = null)
    {
        return $this->auth->getLoginURL($returnTo);
    }

    public function getUsername()
    {
        if ($this->isAuthenticated()) {
            if (array_key_exists($this->userIdAttribute, $this->getAttributes())) {
                $attributes = $this->getAttributes();

                return $attributes[$this->userIdAttribute][0];
            }

            throw new InvalidArgumentException(sprintf(
                'Your Identity Provider must return attribute "%s".',
                $this->userIdAttribute
            ));
        }
    }
}
