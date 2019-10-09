<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\TodoRepository;

use EventSauce\EventSourcing\AggregateRootRepository;
use TodoApp\Domain\Model\Todo\Exception\TodoAlreadyExist;
use TodoApp\Domain\Model\Todo\Exception\TodoNotFound;
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

    /**
     * @param Todo $todo
     *
     * @throws TodoAlreadyExist
     */
    public function addNewTodo(Todo $todo): void
    {
        // TODO: UGLY UGLY UGLY!!!
        // maybe the trait TodoAggregateRootBehaviourWithRequiredHistory should be return 0 or a special object User::unknow
        try {
            $this->todoAggregateRootRepository->retrieve($todo->id());
        } catch (TodoNotFound $throwable) {
            $this->todoAggregateRootRepository->persist($todo);

            return;
        }

        throw TodoAlreadyExist::withTodoId($todo->id());
    }

    /**
     * @param Todo $todo
     *
     * @throws TodoNotFound
     */
    public function save(Todo $todo): void
    {
        if ($this->todoAggregateRootRepository->retrieve($todo->id())) {
            $this->todoAggregateRootRepository->persist($todo);
        }
    }

    /**
     * @param TodoId $todoId
     *
     * @return Todo|null
     *
     * @throws TodoNotFound
     */
    public function ofId(TodoId $todoId): ?Todo
    {
        return $this->todoAggregateRootRepository->retrieve($todoId);
    }
}
