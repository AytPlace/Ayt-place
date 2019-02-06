<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 15:50
 */

namespace App\DataFixtures;


use App\Entity\Recipient;
use App\Service\RandomStringGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RecipientFixture extends Fixture
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
        $user = new Recipient();
        $user->setFirstname("alexandre")
            ->setLastname("vagnair")
            ->setCity("Paris")
            ->setCountry("France")
            ->setBornDate(new \DateTime('1998-01-26'))
            ->setPhoneNumber("0660566104")
            ->setGender("monsieur")
            ->setZipcode("75013")
            ->setSiren("111111111")
            ->setEmail("alexandre.vagnair@sooyoos.com")
            ->setPassword('root')
            ->setRoles(["ROLE_RECIPIENT"]);

        $manager->persist($user);
        $manager->flush();
    }
}