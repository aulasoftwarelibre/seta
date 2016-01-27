<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 26/12/15
 * Time: 07:28.
 */
namespace Seta\LockerBundle\Exception;

/**
 * Class BusyLockerException.
 */
class BusyLockerException extends \RuntimeException
{
    protected $message = 'La taquilla ya se encuentra alquilada';
}
