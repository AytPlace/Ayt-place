<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 19:17
 */

namespace App\EventListener;


use App\Entity\Client;
use App\Entity\Recipient;
use App\Entity\User;
use App\Repository\StatusRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Proxies\__CG__\App\Entity\Status;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Recipient || $entity instanceof Client) {
            $entity->setPassword($this->userPasswordEncoder->encodePassword($entity, $entity->getPassword()));

            if ($entity instanceof Recipient) {
                $entity->setStatus(User::STATUS["A valider"]);
                $entity->setRoles(['ROLE_RECIPIENT']);
            }

            if ($entity instanceof Client) {
                $entity->setRoles(['ROLE_CLIENT']);
            }
        }
    }
}