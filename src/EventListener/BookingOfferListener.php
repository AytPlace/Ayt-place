<?php


namespace App\EventListener;


use App\Entity\Client;
use App\Entity\Recipient;
use App\Entity\Request;
use App\Entity\Response;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BookingOfferListener
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
 
        if ($entity instanceof Response) {
            if ($user instanceof Recipient) {
                $entity->SetSender("recipient");
            }elseif ($user instanceof Client) {
                $entity->SetSender("client");
            }
        }
    }
}
