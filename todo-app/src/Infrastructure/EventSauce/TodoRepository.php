<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce;

use EventSauce\EventSourcing\AggregateRootRepository;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoRepository as TodoRepositoryPort;

class TodoRepository implements TodoRepositoryPort
{
    /**
     * @var AggregateRootRepository
     */
    private $aggregateRootRepository;

    public function __construct(AggregateRootRepository $aggregateRootRepository)
    {
        $this->aggregateRootRepository = $aggregateRootRepository;
    }

    public function store(Todo $todo): void
    {
        $this->aggregateRootRepository->persist($todo);
    }

    public function ofId(TodoId $todoId): Todo
    {
        /** @var Todo $todo */
        $todo = $this->aggregateRootRepository->retrieve($todoId);

        return $todo;
    }
}
