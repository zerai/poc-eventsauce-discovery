<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;

class MarkTodoAsDone implements SerializablePayload
{
    /** @var TodoId */
    private $todoId;

    /**
     * MarkTodoAsDone constructor.
     *
     * @param TodoId $todoId
     */
    public function __construct(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId()->toString(),
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new MarkTodoAsDone(
            TodoId::fromString($payload['todo_id'])
        );
    }

    /**
     * @return TodoId
     */
    public function todoId(): TodoId
    {
        return $this->todoId;
    }
}
