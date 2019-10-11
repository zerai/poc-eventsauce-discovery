<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\TodoRepository;

class AddReminderToTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    /**
     * AddReminderToTodoHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param AddReminderToTodo $command
     *
     * @throws \TodoApp\Domain\Model\Todo\Exception\InvalidReminder
     * @throws \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function __invoke(AddReminderToTodo $command)
    {
        $todo = $this->todoRepository->ofId($command->todoId());

        $todo->addReminder($command->userId(), $command->reminder());

        $this->todoRepository->save($todo);
    }
}
