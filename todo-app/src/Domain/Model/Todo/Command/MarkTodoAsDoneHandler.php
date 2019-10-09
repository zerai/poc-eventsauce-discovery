<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\Exception\TodoNotFound;
use TodoApp\Domain\Model\Todo\TodoRepository;

class MarkTodoAsDoneHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    /**
     * PostTodoHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param MarkTodoAsDone $command
     *
     * @throws TodoNotFound
     */
    public function __invoke(MarkTodoAsDone $command)
    {
        $todo = $this->todoRepository->ofId($command->todoId());

        $todo->markAsDone();

        $this->todoRepository
            ->save($todo);
    }
}
