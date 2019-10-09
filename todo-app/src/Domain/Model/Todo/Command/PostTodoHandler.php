<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoRepository;

class PostTodoHandler
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

    public function __invoke(PostTodo $command)
    {
        $this->todoRepository
            ->addNewTodo(
                Todo::post(
                    $command->todoId(),
                    $command->todoText(),
                    $command->assigneeId()
                )
            );
    }
}
