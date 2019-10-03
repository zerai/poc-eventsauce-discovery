<?php

namespace TodoApp\Domain\Model\Todo\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasMarkedAsDone implements SerializablePayload
{
    /** @var TodoId */
    private $todoId;

    /** @var TodoStatus */
    private $newStatus;

    /** @var UserId */
    private $assigneeId;

    /**
     * TodoWasMarkedAsDone constructor.
     *
     * @param TodoId     $todoId
     * @param TodoStatus $newStatus
     * @param UserId     $assigneeId
     */
    public function __construct(TodoId $todoId, TodoStatus $newStatus, UserId $assigneeId)
    {
        $this->todoId = $todoId;
        $this->newStatus = $newStatus;
        $this->assigneeId = $assigneeId;
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId()->toString(),
            'new_status' => $this->newStatus()->toString(),
            'assignee_id' => $this->assigneeId()->toString(),
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            TodoId::fromString($payload['todo_id']),
            TodoStatus::fromName($payload['new_status']),
            UserId::fromString($payload['assignee_id'])
        );
    }

    /**
     * @return TodoId
     */
    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    /**
     * @return TodoStatus
     */
    public function newStatus(): TodoStatus
    {
        return $this->newStatus;
    }

    /**
     * @return UserId
     */
    public function assigneeId(): UserId
    {
        return $this->assigneeId;
    }
}
