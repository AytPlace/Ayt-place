<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/12/18
 * Time: 16:50
 */

namespace App\EventListener;


use App\Entity\User;
use App\Service\EmailManager;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserListener
{
    private $emailSender;

    public function __construct(EmailManager $emailManager)
    {
        $this->emailSender = $emailManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
    }
}