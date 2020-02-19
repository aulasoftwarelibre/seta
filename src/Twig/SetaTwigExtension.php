<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Twig;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SetaTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sort_rental', [$this, 'sortRental']),
        ];
    }

    public function sortRental(PersistentCollection $rentals, $limit = 10)
    {
        $criteria = Criteria::create()
            ->orderBy(['startAt' => Criteria::DESC])
            ->setMaxResults($limit)
        ;

        return $rentals->matching($criteria);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'seta_extension';
    }
}
