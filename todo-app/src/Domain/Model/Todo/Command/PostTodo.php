<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

class PostTodo implements SerializablePayload
{
    /** @var TodoId */
    private $todoId;

    /** @var string */
    private $todoText;

    /** @var UserId */
    private $assigneeId;

    /**
     * PostTodo constructor.
     *
     * @param TodoId $todoId
     * @param string $todoText
     * @param UserId $assigneeId
     */
    public function __construct(TodoId $todoId, string $todoText, UserId $assigneeId)
    {
        $this->todoId = $todoId;
        $this->todoText = $todoText;
        $this->assigneeId = $assigneeId;
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId(),
            'todo_text' => $this->todoText(),
            'assigee_id' => $this->assigneeId(),
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new PostTodo(
            TodoId::fromString($payload['todo_id']),
            (string) $payload['todo_text'],
            UserId::fromString($payload['assigee_id'])
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
}
