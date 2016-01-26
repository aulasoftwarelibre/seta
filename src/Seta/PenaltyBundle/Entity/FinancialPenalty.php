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
 * Class FinancialPenalty
 * @package Seta\PenaltyBundle\Entity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Seta\PenaltyBundle\Repository\FinancialPenaltyRepository")
 */
class FinancialPenalty extends Penalty
{
    /**
     * @var float
     *
     * @ORM\Column(name="ammount", type="float")
     */
    private $ammount;

    /**
     * @var boolean
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
     * Set ammount
     *
     * @param float $ammount
     *
     * @return FinancialPenalty
     */
    public function setAmmount($ammount)
    {
        $this->ammount = $ammount;

        return $this;
    }

    /**
     * Get ammount
     *
     * @return float
     */
    public function getAmmount()
    {
        return $this->ammount;
    }

    /**
     * Set isPaid
     *
     * @param boolean $isPaid
     *
     * @return FinancialPenalty
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }
}
