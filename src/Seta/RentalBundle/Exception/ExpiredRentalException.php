<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 21:26
 */

namespace Seta\RentalBundle\Exception;


class ExpiredRentalException extends \RuntimeException
{
    protected $message = 'El alquiler ha caducado.';
}
