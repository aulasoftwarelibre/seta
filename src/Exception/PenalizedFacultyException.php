<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

class PenalizedFacultyException extends \RuntimeException
{
    protected $message = 'Su centro no está habilitado para el préstamo de taquillas';
}
