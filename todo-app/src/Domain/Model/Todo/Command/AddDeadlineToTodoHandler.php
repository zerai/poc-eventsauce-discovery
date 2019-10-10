<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\TodoRepository;

class AddDeadlineToTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    /**
     * AddDeadlineToTodoHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param AddDeadlineToTodo $command
     *
     * @throws \TodoApp\Domain\Model\Todo\Exception\InvalidDeadline
     * @throws \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function __invoke(AddDeadlineToTodo $command)
    {
        $todo = $this->todoRepository->ofId($command->todoId());

        $todo->addDeadline($command->userId(), $command->deadline());

        $this->todoRepository
            ->save($todo);
    }
}
