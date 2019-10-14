<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

class SendTodoReminderMail implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    /** @var UserId */
    private $userId;

    /**
     * SendTodoReminderMail constructor.
     *
     * @param TodoId $todoId
     * @param UserId $userId
     */
    public function __construct(UserId $userId, TodoId $todoId)
    {
        $this->todoId = $todoId;
        $this->userId = $userId;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new SendTodoReminderMail(
            UserId::fromString($payload['user_id']),
            TodoId::fromString($payload['todo_id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'user_id' => $this->todoId->toString(),
            'todo_id' => $this->todoId->toString(),
        ];
    }

    public static function with(UserId $userId, TodoId $todoId): SendTodoReminderMail
    {
        return new SendTodoReminderMail(
            $userId,
            $todoId
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
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }
}
