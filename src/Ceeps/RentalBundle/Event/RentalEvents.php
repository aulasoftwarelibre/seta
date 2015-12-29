<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/12/15
 * Time: 13:03
 */

namespace Ceeps\RentalBundle\Event;


class RentalEvents
{
    /**
     * Evento que controla que una nueva taquilla ha sido prestada
     */
    const LOCKER_RENTED = 'seta.locker.rented';

    /**
     * Evento que controla que una taquilla ha renovado el préstamo
     */
    const LOCKER_RENEWED = 'seta.locker.renewed';

    /**
     * Evento que controla que una taquilla ha sido devuelta
     */
    const LOCKER_RETURNED = 'seta.locker.returned';
}