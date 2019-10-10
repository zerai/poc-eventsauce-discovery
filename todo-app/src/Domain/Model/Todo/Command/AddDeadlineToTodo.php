<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoDeadline;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

final class AddDeadlineToTodo implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var TodoDeadline
     */
    private $deadline;

    public function __construct(
        TodoId $todoId,
        UserId $userId,
        TodoDeadline $deadline
    ) {
        $this->todoId = $todoId;
        $this->userId = $userId;
        $this->deadline = $deadline;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function deadline(): TodoDeadline
    {
        return $this->deadline;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new AddDeadlineToTodo(
            TodoId::fromString($payload['todo_id']),
            UserId::fromString($payload['user_id']),
            TodoDeadline::fromString($payload['deadline'])
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
            'user_id' => $this->userId->toString(),
            'deadline' => $this->deadline->toString(),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withTodoIdAndUserIdAndDeadline(TodoId $todoId, UserId $userId, TodoDeadline $deadline): AddDeadlineToTodo
    {
        return new AddDeadlineToTodo(
            $todoId,
            $userId,
            $deadline
        );
    }
}
