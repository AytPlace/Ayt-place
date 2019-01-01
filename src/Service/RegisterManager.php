<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 27/12/18
 * Time: 14:11
 */

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegisterManager
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getDuplicateEmail($email)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!is_null($user)) {
            return true;
        }

        return false;
    }

    public function verificationEmail()
    {
        
    }
}