<?php

declare(strict_types=1);

namespace App\Command;

use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;

class WireUserAddCommand extends Command
{
    /** @var AggregateRootRepository */
    private $userRepository;

    public function __construct(AggregateRootRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->setDescription('Wire add user');
        parent::__construct('wire:user:add');
    }

    protected function configure(): void
    {
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = UserId::create();

        $user = User::register($userId, 'j. doe', 'me@jdoe.come');

        $this->userRepository->persist($user);
    }
}
