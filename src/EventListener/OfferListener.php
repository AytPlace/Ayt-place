<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 21:15
 */

namespace App\EventListener;


use App\Entity\Offer;
use App\Entity\Recipient;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OfferListener
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if ($entity instanceof Offer && $user instanceof Recipient) {
            $entity->setRecipient($user);
        }
    }
}