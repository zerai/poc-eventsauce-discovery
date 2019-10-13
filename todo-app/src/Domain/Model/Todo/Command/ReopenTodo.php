<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;

final class ReopenTodo implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    public function __construct(
        TodoId $todoId
    ) {
        $this->todoId = $todoId;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new ReopenTodo(
            TodoId::fromString($payload['todo_id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
        ];
    }

    public static function withTodoId(TodoId $todoId): ReopenTodo
    {
        return new ReopenTodo(
            $todoId
        );
    }
}
