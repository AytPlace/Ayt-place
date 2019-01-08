<?php

namespace App\DataFixtures;

use App\Entity\Status;
use App\Entity\User;
use App\Service\RandomStringGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StatusFixtures extends Fixture
{
    public function load( ObjectManager $manager)
    {
        $status = new Status();
        $status->setName("a valider")
            ->setColor("#ff0066")
        ;


        $manager->persist($status);
        $manager->flush();
    }
}
