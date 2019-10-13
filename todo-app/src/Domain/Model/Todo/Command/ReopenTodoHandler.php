<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\TodoRepository;

class ReopenTodoHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    /**
     * ReopenTodoHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function __invoke(ReopenTodo $command)
    {
        $todo = $this->todoRepository->ofId($command->todoId());

        $todo->reopenTodo();

        $this->todoRepository->save($todo);
    }
}
