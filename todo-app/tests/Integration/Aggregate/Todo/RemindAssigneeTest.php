<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\RemindTodoAssignee;
use TodoApp\Domain\Model\Todo\Command\RemindTodoAssigneeHandler;
use TodoApp\Domain\Model\Todo\Event\ReminderWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoAssigneeWasReminded;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\InvalidReminder;
use TodoApp\Domain\Model\Todo\Exception\TodoNotOpen;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class RemindAssigneeTest extends PostTodoTestCase
{
    private const FUTURE_REMINDER_DATE = '2050-01-01 10:00:00';
    private const PAST_REMINDER_DATE = '1900-01-01 10:00:00';

    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new RemindTodoAssigneeHandler($repository); //, $clock
    }

    /** @test */
    public function allow_remind_assignee(): void
    {
        $todoId = $this->aggregateRootId();

        $reminderInPast = TodoReminder::from(self::PAST_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new ReminderWasAddedToTodo($todoId, $assignee, $reminderInPast)
        )->when(
            new RemindTodoAssignee($todoId, $reminderInPast, $reminderInPast->status())
        )->then(
            new TodoAssigneeWasReminded($todoId, $assignee, $reminderInPast->close())
        );
    }

    /** @test */
    public function deny_remind_if_todo_is_in_the_future(): void
    {
        self::markTestSkipped('this exception is silently caught in the command handler');
        $todoId = $this->aggregateRootId();

        $reminder = TodoReminder::from(self::FUTURE_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new ReminderWasAddedToTodo($todoId, $assignee, $reminder)
        )->when(
            new RemindTodoAssignee($todoId, $reminder, TodoReminderStatus::OPEN())
        )->expectToFail(
            InvalidReminder::reminderInTheFuture($reminder)
        );
    }

    /** @test */
    public function deny_remind_if_todo_is_already_reminded(): void
    {
        self::markTestSkipped('this exception is silently caught in the command handler');

        $todoId = $this->aggregateRootId();

        $reminder = TodoReminder::from(self::PAST_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new ReminderWasAddedToTodo($todoId, $assignee, $reminder),
            new TodoAssigneeWasReminded($todoId, $assignee, $reminder->close())
        )->when(
            new RemindTodoAssignee($todoId, $reminder, TodoReminderStatus::OPEN())
        )->expectToFail(
            InvalidReminder::alreadyReminded()
        );
    }

    /** @test */
    public function deny_remind_if_todo_is_done(): void
    {
        $todoId = $this->aggregateRootId();

        $reminder = TodoReminder::from(self::PAST_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::DONE()),
            new ReminderWasAddedToTodo($todoId, $assignee, $reminder)
        )->when(
            new RemindTodoAssignee($todoId, $reminder, TodoReminderStatus::OPEN())
        )->expectToFail(
            TodoNotOpen::triedToAddReminder($reminder, $this->retrieveAggregateRoot($todoId)->status())
        );
    }

    /** @test */
    public function deny_remind_if_is_not_current_reminder(): void
    {
        self::markTestSkipped('need setup...');

        $todoId = $this->aggregateRootId();

        $currentReminder = TodoReminder::from(self::PAST_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $otherPastReminder = TodoReminder::from('1000-01-01 10:00:00', $reminderStatus = TodoReminderStatus::OPEN()->name());

        $otherFutureReminder = TodoReminder::from(self::FUTURE_REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->name());

        $this->given(
            new TodoWasPosted($todoId, 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN()),
            new ReminderWasAddedToTodo($todoId, $assignee, $currentReminder)
        )->when(
            new RemindTodoAssignee($todoId, $otherPastReminder, TodoReminderStatus::OPEN())
        )->expectToFail(
            InvalidReminder::reminderNotCurrent($currentReminder, $otherPastReminder)
        );
        //self::assertFalse($currentReminder->sameValueAs($otherReminder));
    }
}
