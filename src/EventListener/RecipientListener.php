<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 19:17
 */

namespace App\EventListener;


use App\Entity\Recipient;
use App\Repository\StatusRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RecipientListener
{
    private $statusRepository;

    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Recipient) {
            $status = $this->statusRepository->findOneBy(['name' => 'a valider']);
            $entity->setStatus($status);
        }
    }
}