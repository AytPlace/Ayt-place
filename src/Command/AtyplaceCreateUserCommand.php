<?php

namespace App\Command;

use App\Entity\Client;
use App\Entity\Recipient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AtyplaceCreateUserCommand extends Command
{

    private $em;


    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->em = $entityManager;

        parent::__construct();
    }

    protected static $defaultName = 'atyplace:create:user';

    protected function configure()
    {
        $this
            ->setDescription('Add a user to database')
            ->setHelp('This command is for adding a user in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Select new user type',
            ['recipient', 'client']

        );

        $question->setErrorMessage("User type %s is invalide");

        $user = $helper->ask($input, $output, $question);

        $questionGender= new ChoiceQuestion("Are you a man or a women  ? ",['man', 'women']);
        $questionGender->setErrorMessage("User gender is invalide");

        $user = $helper->ask($input, $output, $questionGender);


        $questionFirstName = new Question("what's is the first name for user :");
        $firstname = $helper->ask($input, $output, $questionFirstName);

        $questionLastName = new Question("what's is the last name for user :");
        $lastname = $helper->ask($input, $output, $questionLastName);

        $questionCity = new Question("Where do you live ? ");
        $city= $helper->ask($input, $output, $questionCity);

        $questionZipCode = new Question("what is your zip Code ? ");
        $zipcode= $helper->ask($input, $output, $questionZipCode);

        $questionCountry= new Question("what is your country ? ");
        $country= $helper->ask($input, $output, $questionCountry);

        $questionBornDate= new Question("what is your birthday ? ");
        $borndate= $helper->ask($input, $output, $questionBornDate);


        $questionPhoneNumber= new Question("what is your telephone Number  ? ");
        $phonenumber= $helper->ask($input, $output, $questionPhoneNumber);

        $questionEmail= new Question("what is your email  ? ");
        $email= $helper->ask($input, $output, $questionEmail);

        $questionPassword= new Question("what is your password  ? ");
        $password= $helper->ask($input, $output, $questionPassword);


        $questionSiren= new Question("what is your siren? ");
        $siren= $helper->ask($input, $output, $questionSiren);







        if ($user === "recipient") {


            $newUser = new Recipient();
            $newUser->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setZipcode($zipcode)
                    ->setCity($city)
                    ->setZipcode($zipcode)
                    ->setCountry($country)
                    ->setBorndate(new \DateTime(strftime($borndate)))
                    ->setPhonenumber($phonenumber)
                    ->setGender($questionGender)
                    ->setEmail($email)
                    ->setPassword($password)
                    ->setSiren($siren)
            ;

        }else {
            $newUser = new Client();
            $newUser->setFirstname($firstname)
                ->setLastname($lastname)
                ->setZipcode($zipcode)
                ->setCity($city)
                ->setZipcode($zipcode)
                ->setCountry($country)
                ->setBorndate($borndate)
                ->setPhonenumber($phonenumber)
                ->setGender($questionGender)
                ->setEmail($email)
                ->setPassword($password)
            ;
        }

        $this->em->persist($newUser);
        $this->flush();

    }
}