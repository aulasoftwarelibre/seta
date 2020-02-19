<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/12/15
 * Time: 16:06.
 */
namespace App\Exception;

class PenalizedUserException extends \RuntimeException
{
    protected $message = 'El usuario está sancionado.';
}
