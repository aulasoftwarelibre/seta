<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 15/02/16
 * Time: 10:17
 */

namespace App\EventListener;


use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class NewUserSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_PERSIST => 'configureUser',
        ];
    }

    public function configureUser(GenericEvent $event)
    {
        /** @var User $subject */
        $subject = $event->getSubject();

        if ($subject instanceof User === false) {
            return;
        }

        $subject->setPlainPassword(sha1(openssl_random_pseudo_bytes(128)));
        $subject->setEnabled(true);
    }
}
