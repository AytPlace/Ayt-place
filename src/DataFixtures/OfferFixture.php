<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 15:50
 */

namespace App\DataFixtures;


use App\Entity\Offer;
use App\Entity\Request;
use App\Repository\ClientRepository;
use App\Repository\OfferRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OfferFixture extends Fixture implements DependentFixtureInterface
{

    private $clientRepository;

    private $offerRepository;

    private $recipientRepository;

    public function __construct( ClientRepository $clientRepository, OfferRepository $offerRepository, RecipientRepository $recipientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->offerRepository = $offerRepository;
        $this->recipientRepository = $recipientRepository;
    }


    public function load( ObjectManager $manager)
    {
        /*$client = $this->clientRepository->findOneBy(['email' => 'alexandre.vagnair@hetic.net']);
        $status = $this->statusRepository->findOneBy(['name' => 'a valider']);
        $recipient = $this->recipientRepository->findOneBy(['email' => 'alexandre.vagnair@sooyoos.com']);
        $offer = new Offer();


        $request = new Request();
        $request->setDescription("<p>test descriuption pour les requêtes</p>")
            ->addClient($client)
            ->setStatus($status)
            ->setOffer($offer)
        ;

        $offer->setDescription("<p>test descriuption pour les offres</p>")
            ->setCity('Paris')
            ->setCostByTraveler(30)
            ->setCountry("France")
            ->setLocation('15 rue de la vistule')
            ->setTitle('test offre')
            ->setRecipient($recipient)
            ->setRequest($request)
            ->setTravelerNumbers(20)
        ;

        $manager->persist($offer);
        $manager->persist($request);
        $manager->flush();*/
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            RecipientFixture::class,
        );
    }
}