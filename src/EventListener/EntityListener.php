<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 22/01/19
 * Time: 22:47
 */

namespace App\EventListener;


use App\Entity\AvailabilityOffer;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User || $entity instanceof Offer || $entity instanceof AvailabilityOffer) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ($entity instanceof User || $entity instanceof Offer || $entity instanceof AvailabilityOffer) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}