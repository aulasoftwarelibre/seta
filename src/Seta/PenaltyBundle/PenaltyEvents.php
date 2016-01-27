<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\PenaltyBundle;

final class PenaltyEvents
{
    /**
     * Evento que se lanza cuando se crea una penalización.
     */
    const PENALTY_CREATED = 'seta.penalty.created';

    /**
     * Evento que se lanza cuando se cierra una penalización.
     */
    const PENALTY_CLOSED = 'seta.penalty.closed';
}
