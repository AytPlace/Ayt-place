<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use App\Service\RandomStringGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    private $randomStringGenerator;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, RandomStringGenerator $randomStringGenerator)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->randomStringGenerator = $randomStringGenerator;
    }

    public function load( ObjectManager $manager)
    {
        $user = new Client();
        $user->setFirstname("alexandre")
            ->setLastname("vagnair")
            ->setCity("Paris")
            ->setCountry("France")
            ->setBornDate(new \DateTime('1998-01-26'))
            ->setPhoneNumber("0660566104")
            ->setGender("monsieur")
            ->setZipcode("75013")
            ->setEmail("alexandre.vagnair@hetic.net")
            ->setPassword('root')
            ->setRoles(["ROLE_CLIENT"]);

        $manager->persist($user);
        $manager->flush();
    }
}
