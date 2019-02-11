<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 06/02/19
 * Time: 18:15
 */

namespace App\EventListener;


use App\Entity\Request;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;


class BookingRequestListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ( $entity instanceof Request) {
            $entity->setStatus(User::STATUS["A valider"]);
        }
    }
}