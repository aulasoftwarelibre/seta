<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Seta\PenaltyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class FinancialPenalty.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Seta\PenaltyBundle\Repository\FinancialPenaltyRepository")
 */
class FinancialPenalty extends Penalty
{
    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_paid", type="boolean")
     */
    private $isPaid;

    /**
     * FinancialPenalty constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->isPaid = false;
    }

    /**
     * Set ammount.
     *
     * @param float $amount
     *
     * @return FinancialPenalty
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get ammount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set isPaid.
     *
     * @param bool $isPaid
     *
     * @return FinancialPenalty
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid.
     *
     * @return bool
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        parent::close();

        $this->setIsPaid(true);
    }
}
