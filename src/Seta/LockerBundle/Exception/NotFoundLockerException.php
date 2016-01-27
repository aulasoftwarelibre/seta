<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/12/15
 * Time: 15:25.
 */
namespace Seta\LockerBundle\Exception;

/**
 * Class NotFoundLockerException.
 */
class NotFoundLockerException extends \RuntimeException
{
    protected $message = 'No se ha encontrado la taquilla.';
}
