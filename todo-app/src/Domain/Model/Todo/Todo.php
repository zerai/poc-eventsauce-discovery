<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\User\UserId;

class Todo implements AggregateRoot
{
    use AggregateRootBehaviour;

    /** @var TodoId */
    private $id;

    /** @var string */
    private $todoText;

    /** @var UserId */
    private $assignee;

    /** @var TodoStatus */
    private $status;

    public static function post(TodoId $todoId, string $todoText, UserId $assigneeId, TodoStatus $status): Todo
    {
        $self = new self($todoId);
        $self->recordThat(
            TodoWasPosted::fromPayload([
                'todo_id' => $todoId->toString(),
                'todo_text' => $todoText,
                'user_id' => $assigneeId->toString(),
                'status' => $status->toString(),
                ])
        );

        return $self;
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
}