<?php

namespace Sgomez\Bundle\CeepsUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CeepsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
