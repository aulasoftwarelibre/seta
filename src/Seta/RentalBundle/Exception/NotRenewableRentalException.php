<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 21:25.
 */
namespace Seta\RentalBundle\Exception;

class NotRenewableRentalException extends \RuntimeException
{
    protected $message = 'Este alquiler está marcado como no renovable.';
}
