<?php

namespace NS\SimpleUserBundle\Command;

use NS\SimpleUserBundle\Entity\User\Exception\UserNotFoundException;
use NS\SimpleUserBundle\Entity\User\User;
use NS\SimpleUserBundle\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeUserPasswordCommand extends Command
{
    /**
     * @var UserService
     */
    protected $user_service;

    public function __construct(UserService $user_service)
    {
        parent::__construct('ns:user:reset-password');
        $this->user_service = $user_service;
    }

    protected function configure(): void
    {
        $this->setDescription('Reset a User\'s password')
             ->setDefinition([
                                 new InputArgument(
                                     'user',
                                     InputArgument::REQUIRED,
                                     'Email address'
                                 ),
                                 new InputArgument(
                                     'password',
                                     InputArgument::REQUIRED,
                                     'New Password'
                                 ),
                             ]);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try
        {
            $this->user_service->updateUserPassword($input->getArgument('user'), $input->getArgument('password'));
            $output->writeln('User ' . $input->getArgument('user') . ' updated.');

            return 0;
        }
        catch(UserNotFoundException $e)
        {
            $output->writeln('User not found');
        }

        return 1;
    }
}
