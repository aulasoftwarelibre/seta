<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event;

use App\Entity\Penalty;
use Symfony\Contracts\EventDispatcher\Event;

class PenaltyEvent extends Event
{
    /**
     * @var Penalty
     */
    private $penalty;

    /**
     * PenaltyEvent constructor.
     *
     * @param Penalty $penalty
     */
    public function __construct(Penalty $penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * Get Penalty.
     *
     * @return Penalty
     */
    public function getPenalty()
    {
        return $this->penalty;
    }
}
