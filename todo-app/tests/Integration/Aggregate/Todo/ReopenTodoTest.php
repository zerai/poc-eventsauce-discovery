<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\ReopenTodo;
use TodoApp\Domain\Model\Todo\Command\ReopenTodoHandler;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Event\TodoWasReopened;
use TodoApp\Domain\Model\Todo\Exception\CannotReopenTodo;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class ReopenTodoTest extends PostTodoTestCase
{
    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new ReopenTodoHandler($repository);
    }

    /** @test */
    public function allow_reopen_todo(): void
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new TodoWasMarkedAsDone($todoId, TodoStatus::DONE(), $assignee)
        )->when(
            new ReopenTodo($todoId)
        )->then(
            new TodoWasReopened($todoId, TodoStatus::OPEN(), $assignee)
        );
    }

    /** @test */
    public function deny_reopen_todo_if_todo_status_is_not_done(): void
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new TodoWasMarkedAsExpired($todoId, TodoStatus::OPEN(), TodoStatus::EXPIRED(), $assignee)
        )->when(
            new ReopenTodo($todoId)
        )->expectToFail(
            CannotReopenTodo::notMarkedDone($this->retrieveAggregateRoot($todoId))
        );
    }
}
