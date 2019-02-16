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
use App\Service\EmailManager;
use App\Service\RandomStringGenerator;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Proxies\__CG__\App\Entity\Status;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{

    private $userPasswordEncoder;

    private $randomStringGenerator;

    private $emailManager;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, RandomStringGenerator $randomStringGenerator, EmailManager $emailManager)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->emailManager = $emailManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Recipient || $entity instanceof Client) {
            $entity->setPassword($this->userPasswordEncoder->encodePassword($entity, $entity->getPassword()));

            $entity->setEnableToken($this->randomStringGenerator->generate());
            $entity->setEnable(false);
            $this->emailManager->sendEnableEmail($entity);

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