<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 11:08.
 */
namespace App\Exception;

/**
 * Class NotRentedLockerException.
 */
class NotRentedLockerException extends \RuntimeException
{
    protected $message = 'La taquilla no está alquilada.';
}
