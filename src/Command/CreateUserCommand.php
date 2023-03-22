<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'user:create', description: 'Create a test user.')]
class CreateUserCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');

        $email = $questionHelper->ask($input, $output, new Question('<info>Email: </info>'));
        $password = $questionHelper->ask($input, $output, new Question('<info>Password: </info>'));
        $name = $questionHelper->ask($input, $output, new Question('<info>Name: </info>'));

        $output->writeln(
            sprintf(
                '<comment>Электронный адрес - %s, Пароль - %s, Имя - %s</comment>',
                $email, $password, $name
            )
        );

        return Command::SUCCESS;
    }
}