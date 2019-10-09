<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use TodoApp\Domain\Model\Todo\Command\PostTodoHandler;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoRepository;

abstract class PostTodoTestCase extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return TodoId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return Todo::class;
    }

    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new PostTodoHandler($repository); //, $clock
    }

    protected function handle($command): void
    {
        $commandHandler = $this->commandHandler($this->setupTodoRepository()); //, $this->clock()
        ($commandHandler)($command);
    }

    public function setupTodoRepository(): TodoRepository
    {
        return new \TodoApp\Infrastructure\EventSauce\TodoRepository\TodoRepository($this->repository);
    }
}
