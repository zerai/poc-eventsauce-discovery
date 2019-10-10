<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\TodoRepository;

class MarkTodoAsExpiredHandler
{
    /** @var TodoRepository */
    private $todoRepository;

    /**
     * MarkTodoAsExpiredHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param MarkTodoAsExpired $event
     *
     * @throws \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function __invoke(MarkTodoAsExpired $event): void
    {
        $todo = $this->todoRepository->ofId($event->todoId());

        $todo->markAsExpired();

        $this->todoRepository->save($todo);
    }
}
