<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

final class TodoWasReopened implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var TodoStatus
     */
    private $status;

    /**
     * @var UserId
     */
    private $assigneeId;

    public function __construct(
        TodoId $todoId,
        TodoStatus $status,
        UserId $assigneeId
    ) {
        $this->todoId = $todoId;
        $this->status = $status;
        $this->assigneeId = $assigneeId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function status(): TodoStatus
    {
        return $this->status;
    }

    public function assigneeId(): UserId
    {
        return $this->assigneeId;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new TodoWasReopened(
            TodoId::fromString($payload['todo_id']),
            TodoStatus::fromName($payload['status']),
            UserId::fromString($payload['assignee_id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
            'status' => $this->status->toString(),
            'assignee_id' => $this->assigneeId->toString(),
        ];
    }

    public static function withStatus(TodoId $todoId, TodoStatus $status, UserId $assigneeId): TodoWasReopened
    {
        return new TodoWasReopened(
            $todoId,
            $status,
            $assigneeId
        );
    }
}
