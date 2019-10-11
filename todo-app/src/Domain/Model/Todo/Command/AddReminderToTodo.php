<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;
use TodoApp\Domain\Model\User\UserId;

final class AddReminderToTodo implements SerializablePayload
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
     * @var TodoReminder
     */
    private $reminder;

    public function __construct(
        TodoId $todoId,
        UserId $userId,
        TodoReminder $reminder
    ) {
        $this->todoId = $todoId;
        $this->userId = $userId;
        $this->reminder = $reminder;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function reminder(): TodoReminder
    {
        return $this->reminder;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new AddReminderToTodo(
            TodoId::fromString($payload['todo_id']),
            UserId::fromString($payload['user_id']),
            TodoReminder::from($payload['reminder'], TodoReminderStatus::OPEN()->name())
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
            'user_id' => $this->userId->toString(),
            'reminder' => $this->reminder->toString(),
        ];
    }
}
