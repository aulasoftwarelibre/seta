<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 16:27.
 */
namespace App\Exception;

class TooManyLockersRentedException extends \RuntimeException
{
    protected $message = 'El usuario ya tiene una taquilla y no es posible alquilar otra.';
}
