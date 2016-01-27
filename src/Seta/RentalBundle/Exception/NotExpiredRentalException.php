<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 28/12/15
 * Time: 14:15.
 */
namespace Seta\RentalBundle\Exception;

class NotExpiredRentalException extends \RuntimeException
{
    protected $message = 'El alquiler no ha caducado.';
}
