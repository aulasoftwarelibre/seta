<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 15:27.
 */
namespace App\Exception;

class NotFoundRentalException extends \RuntimeException
{
    protected $message = 'El alquiler no existe.';
}
