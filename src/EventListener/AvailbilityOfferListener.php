<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 22/01/19
 * Time: 22:44
 */

namespace App\EventListener;


use App\Entity\AvailabilityOffer;
use App\Entity\Recipient;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AvailbilityOfferListener
{

    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $token = $this->tokenStorageInterface->getToken();
        $user = $token ? $token->getUser() : null;

        if ($entity instanceof AvailabilityOffer && $user instanceof Recipient) {
            $entity->setAvailable(true);
            $entity->setOffer($user->getOffers());
        }
    }
}