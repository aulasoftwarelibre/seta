<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 20:16
 */

namespace Seta\RentalBundle\Exception;


class TooEarlyRenovationException extends \RuntimeException
{
    protected $message = 'Es demasiado pronto para renovar la taquilla';
}
