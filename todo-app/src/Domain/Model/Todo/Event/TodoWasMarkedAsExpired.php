<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

final class TodoWasMarkedAsExpired implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var TodoStatus
     */
    private $oldStatus;

    /**
     * @var TodoStatus
     */
    private $newStatus;

    /**
     * @var UserId
     */
    private $assigneeId;

    public function __construct(
        TodoId $todoId,
        TodoStatus $oldStatus,
        TodoStatus $newStatus,
        UserId $assigneeId
    ) {
        $this->todoId = $todoId;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->assigneeId = $assigneeId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function oldStatus(): TodoStatus
    {
        return $this->oldStatus;
    }

    public function newStatus(): TodoStatus
    {
        return $this->newStatus;
    }

    public function assigneeId(): UserId
    {
        return $this->assigneeId;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new TodoWasMarkedAsExpired(
            TodoId::fromString($payload['todo_id']),
            TodoStatus::fromName($payload['old_status']),
            TodoStatus::fromName($payload['new_status']),
            UserId::fromString($payload['assignee_id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
            'old_status' => $this->oldStatus->toString(),
            'new_status' => $this->newStatus->toString(),
            'assignee_id' => $this->assigneeId->toString(),
        ];
    }

    public static function fromStatus(TodoId $todoId, TodoStatus $oldStatus, TodoStatus $newStatus, UserId $assigneeId): TodoWasMarkedAsExpired
    {
        return new TodoWasMarkedAsExpired(
            $todoId,
            $oldStatus,
            $newStatus,
            $assigneeId
        );
    }
}
