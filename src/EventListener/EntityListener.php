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
use App\Entity\Request;
use App\Entity\Response;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EntityListener
{
    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User || $entity instanceof Offer || $entity instanceof AvailabilityOffer || $entity instanceof Request) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }

        if ($entity instanceof Response) {
            $token = $this->tokenStorageInterface->getToken();
            $user = $token ? $token->getUser() : null;

            $entity->setUSER($user);
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ($entity instanceof User || $entity instanceof Offer || $entity instanceof AvailabilityOffer || $entity instanceof Request || $entity instanceof Response) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}