<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasPosted implements SerializablePayload
{
    /** @var TodoId */
    private $todoId;

    /** @var string */
    private $todoText;

    /** @var UserId */
    private $assigneeId;

    /** @var TodoStatus */
    private $status;

    /**
     * TodoWasPosted constructor.
     *
     * @param TodoId     $todoId
     * @param string     $todoText
     * @param UserId     $assigneeId
     * @param TodoStatus $status
     */
    public function __construct(TodoId $todoId, string $todoText, UserId $assigneeId, TodoStatus $status)
    {
        $this->todoId = $todoId;
        $this->todoText = $todoText;
        $this->assigneeId = $assigneeId;
        $this->status = $status;
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId()->toString(),
            'todo_text' => $this->todoText(),
            'user_id' => $this->assigneeId()->toString(),
            'status' => $this->status()->toString(),
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new TodoWasPosted(
            TodoId::fromString($payload['todo_id']),
            $payload['todo_text'],
            UserId::fromString($payload['user_id']),
            TodoStatus::fromName($payload['status'])
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
}
