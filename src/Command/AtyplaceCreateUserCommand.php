<?php

namespace App\Command;

use App\Entity\Client;
use App\Entity\Recipient;
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
            ['recipient', 'client'],
            0
        );

        $question->setErrorMessage("User type %s is invalide");

        $user = $helper->ask($input, $output, $question);

        if ($user === "recipient") {
            $newUser = new Recipient();
        }else {
            $newUser = new Client();
        }

    }
}
