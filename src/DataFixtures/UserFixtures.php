<?php

namespace App\DataFixtures;

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
        $user = new User();
        $user->setFirstname("alexandre")
            ->setLastname("vagnair")
            ->setCity("Paris")
            ->setCountry("France")
            ->setBornDate(new \DateTime('1998-01-26'))
            ->setPhoneNumber("0660566104")
            ->setGender("monsieur")
            ->setEmail("alexandre.vagnair@hetic.net")
            ->setPassword($this->userPasswordEncoder->encodePassword($user, 'root'))
            ->setRoles(["ROLE_CLIENT"]);

        $manager->persist($user);
        $manager->flush();
    }
}
