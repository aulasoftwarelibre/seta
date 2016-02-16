<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26/12/15
 * Time: 06:31.
 */
namespace Seta\LockerBundle\Exception;

/**
 * Class NotFreeLockerException.
 */
class NotFreeZoneLockerException extends \RuntimeException
{
    protected $message = 'No hay taquillas libres en la zona solicitada.';
}
