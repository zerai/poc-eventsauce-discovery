<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use EventSauce\EventSourcing\AggregateRoot;
use TodoApp\Domain\Model\Todo\Event\DeadlineWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\ReminderWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoAssigneeWasReminded;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Event\TodoWasReopened;
use TodoApp\Domain\Model\Todo\Event\TodoWasUnmarkedAsExpired;
use TodoApp\Domain\Model\User\UserId;

class Todo implements AggregateRoot
{
    use TodoAggregateRootBehaviourWithRequiredHistory;

    /** @var TodoId */
    private $id;

    /** @var string */
    private $todoText;

    /** @var UserId */
    private $assigneeId;

    /** @var TodoStatus */
    private $status;

    /** @var TodoDeadline|null */
    private $deadline;

    /** @var TodoReminder */
    private $reminder;

    /** @var bool */
    private $reminded = false;

    public static function post(TodoId $todoId, string $todoText, UserId $assigneeId): Todo
    {
        $self = new self($todoId);
        $self->recordThat(
            TodoWasPosted::fromPayload([
                'todo_id' => $todoId->toString(),
                'todo_text' => $todoText,
                'user_id' => $assigneeId->toString(),
                'status' => TodoStatus::OPEN()->toString(),
                ])
        );

        return $self;
    }

    /**
     * @throws Exception\TodoNotOpen
     */
    public function markAsDone(): void
    {
        $newStatus = TodoStatus::DONE();

        if (!$this->status->equals(TodoStatus::OPEN())) {
            throw Exception\TodoNotOpen::triedStatus($newStatus, $this->id());
        }
        $this->recordThat(
            new TodoWasMarkedAsDone($this->id(), $newStatus, $this->assigneeId())
        );
    }

    public function addDeadline(UserId $userId, TodoDeadline $deadline): void
    {
        if (!$this->assigneeId()->equals($userId)) {
            throw Exception\InvalidDeadline::userIsNotAssignee($userId, $this->assigneeId());
        }
        if ($deadline->isInThePast()) {
            throw Exception\InvalidDeadline::deadlineInThePast($deadline);
        }
        if ($this->status->equals(TodoStatus::DONE())) {
            throw Exception\TodoNotOpen::triedToAddDeadline($deadline, $this->status);
        }
        $this->recordThat(DeadlineWasAddedToTodo::byUserToDate($this->id, $this->assigneeId, $deadline));

        // TODO decommentare analisi del perchè
        if ($this->isMarkedAsExpired()) {
            $this->unmarkAsExpired();
        }
    }

    /**
     * @throws Exception\TodoNotExpired
     * @throws Exception\TodoNotOpen
     */
    public function markAsExpired(): void
    {
        $desiredStatus = TodoStatus::EXPIRED();

        if (!$this->status->equals(TodoStatus::OPEN()) || $this->status->equals(TodoStatus::EXPIRED())) {
            throw Exception\TodoNotOpen::triedToExpire($this->status);
        }

        if ($this->deadline->isMet()) {
            throw Exception\TodoNotExpired::withDeadline($this->deadline, $this);
        }
        // TODO haldle this in test
        $this->recordThat(TodoWasMarkedAsExpired::fromStatus($this->id, $this->status, $desiredStatus, $this->assigneeId));
    }

    /**
     * @throws Exception\TodoNotExpired
     */
    public function unmarkAsExpired(): void
    {
        //TODO manca applyTodoWasUnmarkedAsExpired
        $desiredStatus = TodoStatus::OPEN();

        if (!$this->isMarkedAsExpired()) {
            throw Exception\TodoNotExpired::withDeadline($this->deadline, $this);
        }

        $this->recordThat(TodoWasUnmarkedAsExpired::fromStatus($this->id, $this->status, $desiredStatus, $this->assigneeId));
    }

    /**
     * @param UserId       $userId
     * @param TodoReminder $reminder
     *
     * @throws Exception\InvalidReminder
     */
    public function addReminder(UserId $userId, TodoReminder $reminder): void
    {
        if (!$this->assigneeId()->equals($userId)) {
            throw Exception\InvalidReminder::userIsNotAssignee($userId, $this->assigneeId());
        }

        if ($reminder->isInThePast()) {
            throw Exception\InvalidReminder::reminderInThePast($reminder);
        }

        if ($this->status->equals(TodoStatus::DONE())) {
            throw Exception\TodoNotOpen::triedToAddReminder($reminder, $this->status);
        }

        $this->recordThat(ReminderWasAddedToTodo::byUserToDate($this->id, $this->assigneeId, $reminder));
    }

    /**
     * @param TodoReminder $reminder
     *
     * @throws Exception\InvalidReminder
     */
    public function remindAssignee(TodoReminder $reminder): void
    {
        if ($this->status->equals(TodoStatus::DONE())) {
            throw Exception\TodoNotOpen::triedToAddReminder($reminder, $this->status);
        }

        if (!$this->reminder->sameValueAs($reminder)) {
            throw Exception\InvalidReminder::reminderNotCurrent($this->reminder, $reminder);
        }

        if (!$this->reminder->isOpen()) {
            throw Exception\InvalidReminder::alreadyReminded();
        }

        if ($reminder->isInTheFuture()) {
            throw Exception\InvalidReminder::reminderInTheFuture($reminder);
        }

        $this->recordThat(TodoAssigneeWasReminded::forAssignee($this->id, $this->assigneeId, $reminder->close()));
    }

    public function reopenTodo(): void
    {
        if (!$this->status->equals(TodoStatus::DONE())) {
            throw Exception\CannotReopenTodo::notMarkedDone($this);
        }
        $this->recordThat(TodoWasReopened::withStatus($this->id, TodoStatus::OPEN(), $this->assigneeId));
    }

    private function isMarkedAsExpired(): bool
    {
        return $this->status->equals(TodoStatus::EXPIRED());
    }

    /**
     * @return TodoId
     */
    public function id(): TodoId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function todoText(): string
    {
        return $this->todoText;
    }

    /**
     * @return UserId
     */
    public function assigneeId(): UserId
    {
        return $this->assigneeId;
    }

    /**
     * @return TodoStatus
     */
    public function status(): TodoStatus
    {
        return $this->status;
    }

    /**
     * @return TodoDeadline|null
     */
    public function deadline(): ?TodoDeadline
    {
        return $this->deadline;
    }

    /**
     * @return TodoReminder|null
     */
    public function reminder(): ?TodoReminder
    {
        return $this->reminder;
    }

    private function applyTodoWasPosted(TodoWasPosted $event): void
    {
        $this->id = $event->todoId();
        $this->todoText = $event->todoText();
        $this->assigneeId = $event->assigneeId();
        $this->status = $event->status();
    }

    private function applyTodoWasMarkedAsDone(TodoWasMarkedAsDone $event): void
    {
        $this->status = $event->newStatus();
    }

    /**
     * @param TodoWasMarkedAsExpired $event
     */
    private function applyTodoWasMarkedAsExpired(TodoWasMarkedAsExpired $event)
    {
        $this->status = $event->newStatus();
    }

    private function applyDeadlineWasAddedToTodo(DeadlineWasAddedToTodo $event): void
    {
        $this->deadline = $event->deadline();
    }

    private function applyReminderWasAddedToTodo(ReminderWasAddedToTodo $event): void
    {
        $this->reminder = $event->reminder();
        $this->reminded = false;
    }

    private function applyTodoAssigneeWasReminded(TodoAssigneeWasReminded $event): void
    {
        $this->reminder = $event->reminder();
        $this->reminded = true;
    }

    private function applyTodoWasReopened(TodoWasReopened $event): void
    {
        $this->status = $event->status();
    }
}
