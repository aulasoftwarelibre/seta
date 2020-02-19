<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26/12/15
 * Time: 06:31.
 */
namespace App\Exception;

/**
 * Class NotFreeLockerException.
 */
class NotFreeLockerException extends \RuntimeException
{
    protected $message = 'No hay taquillas libres.';
}
