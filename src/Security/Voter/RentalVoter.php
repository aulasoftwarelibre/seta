<?php
/*
 * This file is part of the seta.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace App\Security\Voter;


use App\Entity\Rental;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RentalVoter extends Voter
{

    const RENT = 'rent';

    /**
     * @var int Días antes de renovar
     */
    private $days_before_renovation;

    public function __construct($days_before_renovation)
    {
        $this->days_before_renovation = $days_before_renovation;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::RENT])) {
            return false;
        }

        if (!$subject instanceof Rental) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch($attribute) {
            case self::RENT:
                return $this->canRent($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canRent(Rental $rental, User $user)
    {
        if ($rental->getUser() != $user) {
            return false;
        }

        if ($rental->getReturnAt()) {
            return false;
        }

        if ($user->getIsPenalized()) {
            return false;
        }

        if ($user->getFaculty()->getIsEnabled() === false) {
            return false;
        }

        if (!$rental->getIsRenewable()) {
            return false;
        }

        if ($rental->getIsExpired()) {
            return false;
        }

        if ($rental->getDaysLeft() > $this->days_before_renovation) {
            return false;
        }

        return true;
    }
}
