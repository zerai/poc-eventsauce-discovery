<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\AddDeadlineToTodo;
use TodoApp\Domain\Model\Todo\Command\AddDeadlineToTodoHandler;
use TodoApp\Domain\Model\Todo\Event\DeadlineWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\InvalidDeadline;
use TodoApp\Domain\Model\Todo\TodoDeadline;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class AddDeadlineToTodoTest extends PostTodoTestCase
{
    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new AddDeadlineToTodoHandler($repository); //, $clock
    }

    /** @test */
    public function allow_add_deadline_to_todo()
    {
        self::markTestSkipped('untestable for now!! try to use Clock');
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddDeadlineToTodo($todoId, $assignee, TodoDeadline::fromString('2019-11-11 10:00:00'))
        )->then(
            new DeadlineWasAddedToTodo($this->aggregateRootId(), $assignee, TodoDeadline::fromString('2019-11-11 10:00:00'))
        );
    }

    /** @test */
    public function deny_add_deadline_in_the_past()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddDeadlineToTodo($todoId, $assignee, $deadline = TodoDeadline::fromString('1900-01-01 10:00:00'))
        )->expectToFail(
            InvalidDeadline::deadlineInThePast($deadline)
        );
    }

    /** @test */
    public function deny_add_deadline_if_user_is_not_assigee()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddDeadlineToTodo($todoId, $otherUser = UserId::generate(), $deadline = TodoDeadline::fromString('1900-01-01 10:00:00'))
        )->expectToFail(
            InvalidDeadline::userIsNotAssignee($otherUser, $assignee)
        );
    }
}
