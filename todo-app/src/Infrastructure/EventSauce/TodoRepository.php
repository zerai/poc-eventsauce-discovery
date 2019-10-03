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
    private $todoAggregateRootRepository;

    public function __construct(AggregateRootRepository $todoAggregateRootRepository)
    {
        $this->todoAggregateRootRepository = $todoAggregateRootRepository;
    }

    public function store(Todo $todo): void
    {
        $this->todoAggregateRootRepository->persist($todo);
    }

    public function ofId(TodoId $todoId): Todo
    {
        return $this->todoAggregateRootRepository->retrieve($todoId);
    }
}
