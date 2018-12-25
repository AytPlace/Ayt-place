<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
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
            ->setRoles(["ROLE_USER"]);
        // $product = new Product();
        $manager->persist($user);

        $manager->flush();
    }
}
