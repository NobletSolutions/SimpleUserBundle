<?php


namespace NS\SimpleUserBundle\Command;


use NS\SimpleUserBundle\Entity\User\Exception\UserNotFoundException;
use NS\SimpleUserBundle\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    /**
     * @var UserService
     */
    protected $user_service;

    public function __construct(UserService $user_service)
    {
        parent::__construct('ns:user:create');
        $this->user_service = $user_service;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new user')
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
                                 new InputArgument(
                                     'roles',
                                     InputArgument::IS_ARRAY,
                                     'User roles'
                                 )
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
        $this->user_service->createUser($input->getArgument('user'), $input->getArgument('password'),
                                        $input->getArgument('roles'));
        $output->writeln('User ' . $input->getArgument('user') . ' created.');

        return 0;
    }
}
