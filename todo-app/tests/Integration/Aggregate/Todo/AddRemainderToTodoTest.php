<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\AddReminderToTodo;
use TodoApp\Domain\Model\Todo\Command\AddReminderToTodoHandler;
use TodoApp\Domain\Model\Todo\Event\ReminderWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\InvalidReminder;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class AddRemainderToTodoTest extends PostTodoTestCase
{
    private const FUTURE_REMINDER_DATE = '2050-01-01 10:00:00';
    private const PAST_REMINDER_DATE = '1900-01-01 10:00:00';

    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new AddReminderToTodoHandler($repository); //, $clock
    }

    /** @test */
    public function allow_add_reminder_to_todo()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddReminderToTodo($todoId, $assignee, $reminder = TodoReminder::from(self::FUTURE_REMINDER_DATE, TodoReminderStatus::OPEN()->name()))
        )->then(
            new ReminderWasAddedToTodo($this->aggregateRootId(), $assignee, $reminder)
        );

        self::assertEquals($reminder, $this->retrieveAggregateRoot($todoId)->reminder());
    }

    /** @test */
    public function deny_add_reminder_in_the_past()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddReminderToTodo($todoId, $assignee, $reminder = TodoReminder::from(self::PAST_REMINDER_DATE, TodoReminderStatus::OPEN()->name()))
        )->expectToFail(
            InvalidReminder::reminderInThePast($reminder)
        );
    }

    /** @test */
    public function deny_add_reminder_if_user_is_not_assigee()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new AddReminderToTodo($todoId, $otherUser = UserId::generate(), $reminder = TodoReminder::from(self::PAST_REMINDER_DATE, TodoReminderStatus::OPEN()->name()))
        )->expectToFail(
            InvalidReminder::userIsNotAssignee($otherUser, $assignee)
        );
    }
}
