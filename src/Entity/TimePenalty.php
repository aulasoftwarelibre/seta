<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Penalty;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class TimePenalty.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\TimePenaltyRepository")
 */
class TimePenalty extends Penalty
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $endAt;

    /**
     * TimePenalty constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->startAt = new \DateTime('now');
    }

    /**
     * @param null $today
     *
     * @return \DateTime
     */
    public static function getEndSeasonPenalty($today = null)
    {
        if (!$today) {
            $today = new \DateTime('today');
        }

        $endSeason = new \DateTime('september 1 midnight');
        if ($today >= $endSeason) {
            return new \DateTime('next year september 1 midnight');
        }

        return $endSeason;
    }

    /**
     * Set startAt.
     *
     * @param \DateTime $startAt
     *
     * @return TimePenalty
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt.
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt.
     *
     * @param \DateTime $endAt
     *
     * @return TimePenalty
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
        $this->endAt->setTime(0, 0, 0);

        return $this;
    }

    /**
     * Get endAt.
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }
}
