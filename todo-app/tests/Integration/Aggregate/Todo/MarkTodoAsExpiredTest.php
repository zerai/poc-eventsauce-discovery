<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\MarkTodoAsExpired;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsExpiredHandler;
use TodoApp\Domain\Model\Todo\Event\DeadlineWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\TodoNotExpired;
use TodoApp\Domain\Model\Todo\Exception\TodoNotOpen;
use TodoApp\Domain\Model\Todo\TodoDeadline;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class MarkTodoAsExpiredTest extends PostTodoTestCase
{
    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new MarkTodoAsExpiredHandler($repository); //, $clock
    }

    /** @test */
    public function allow_mark_todo_as_expired()
    {
        self::markTestSkipped('untestable for now!! try to use Clock');

        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new DeadlineWasAddedToTodo($todoId, UserId::generate(), $deadline = TodoDeadline::fromString('1900-01-01 00:00:00'))
        )->when(
            new MarkTodoAsExpired($todoId)
        )->then(
            new TodoWasMarkedAsExpired($todoId, TodoStatus::OPEN(), TodoStatus::EXPIRED(), $assignee)
        );
    }

    /** @test */
    public function deny_mark_todo_as_expired_if_already_expired()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::EXPIRED())
        //new DeadlineWasAddedToTodo($todoId, UserId::generate(), $deadline = TodoDeadline::fromString('2019-11-11 10:00:00')),
        //new TodoWasMarkedAsExpired($todoId, TodoStatus::OPEN(), TodoStatus::EXPIRED(), $assignee)
        )->when(
            new MarkTodoAsExpired($todoId)
        )->expectToFail(
            TodoNotOpen::triedToExpire(TodoStatus::EXPIRED())
        );
    }

    /** @test */
    public function deny_mark_todo_as_expired_if_deadline_is_not_met()
    {
        self::markTestSkipped('untestable for now!! try to use Clock');

        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::EXPIRED()),
            new DeadlineWasAddedToTodo($todoId, UserId::generate(), $deadline = TodoDeadline::fromString('2050-01-01 00:00:00'))
            //new TodoWasMarkedAsExpired($todoId, TodoStatus::OPEN(), TodoStatus::EXPIRED(), $assignee)
        )->when(
            new MarkTodoAsExpired($todoId)
        )->expectToFail(
            TodoNotExpired::withDeadline($deadline, $this->retrieveAggregateRoot($todoId))
        );
    }
}
