<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;

final class RemindTodoAssignee implements SerializablePayload
{
    /**
     * @var TodoId
     */
    private $todoId;

    /**
     * @var TodoReminder
     */
    private $reminder;

    /**
     * @var TodoReminderStatus
     */
    private $reminderStatus;

    public function __construct(
        TodoId $todoId,
        TodoReminder $reminder,
        TodoReminderStatus $reminderStatus
    ) {
        $this->todoId = $todoId;
        $this->reminder = $reminder;
        $this->reminderStatus = $reminderStatus;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function reminder(): TodoReminder
    {
        return $this->reminder;
    }

    public function reminderStatus(): TodoReminderStatus
    {
        return $this->reminderStatus;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new RemindTodoAssignee(
            TodoId::fromString($payload['todo_id']),
            TodoReminder::from($payload['reminder'], $payload['reminder_status']),
            TodoReminderStatus::fromName($payload['reminder_status'])
        );
    }

    public function toPayload(): array
    {
        return [
            'todo_id' => $this->todoId->toString(),
            'reminder' => $this->reminder->toString(),
            'reminder_status' => $this->reminderStatus->toString(),
        ];
    }
}
