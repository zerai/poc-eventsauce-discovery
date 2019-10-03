<?php

declare(strict_types=1);

namespace App\Command;

use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class WireTodoAddCommand extends Command
{
    /** @var AggregateRootRepository */
    private $todoRepository;

    public function __construct(AggregateRootRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
        $this->setDescription('Wire add todo');
        parent::__construct('wire:todo:add');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $todoId = TodoId::generate();

        $assigneeId = UserId::generate();

        $todo = Todo::post($todoId, 'my todo text', $assigneeId, TodoStatus::fromName('OPEN'));

        $this->todoRepository->persist($todo);
    }
}
