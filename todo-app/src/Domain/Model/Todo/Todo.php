<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use EventSauce\EventSourcing\AggregateRoot;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\User\UserId;

class Todo implements AggregateRoot
{
    use TodoAggregateRootBehaviourWithRequiredHistory;

    /** @var TodoId */
    private $id;

    /** @var string */
    private $todoText;

    /** @var UserId */
    private $assignee;

    /** @var TodoStatus */
    private $status;

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
            new TodoWasMarkedAsDone($this->id(), $newStatus, $this->assignee())
        );
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
    public function assignee(): UserId
    {
        return $this->assignee;
    }

    /**
     * @return TodoStatus
     */
    public function status(): TodoStatus
    {
        return $this->status;
    }

    private function applyTodoWasPosted(TodoWasPosted $event): void
    {
        $this->id = $event->todoId();
        $this->todoText = $event->todoText();
        $this->assignee = $event->assigneeId();
        $this->status = $event->status();
    }

    private function applyTodoWasMarkedAsDone(TodoWasMarkedAsDone $event): void
    {
        $this->status = $event->newStatus();
    }
}
