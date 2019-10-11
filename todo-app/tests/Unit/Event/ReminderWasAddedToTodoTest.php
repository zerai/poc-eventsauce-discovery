<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\ReminderWasAddedToTodo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;
use TodoApp\Domain\Model\User\UserId;

class ReminderWasAddedToTodoTest extends TestCase
{
    private const REMINDER_DATE = '2050-01-01 10:00:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::REMINDER_DATE, TodoReminderStatus::OPEN()->name());

        $event = ReminderWasAddedToTodo::fromPayload([
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
        ]);

        self::assertInstanceOf(ReminderWasAddedToTodo::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($reminder->sameValueAs($event->reminder()));
        self::assertTrue($userId->equals($event->userId()));
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::REMINDER_DATE, TodoReminderStatus::OPEN()->name());
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
        ];

        $event = new ReminderWasAddedToTodo($todoId, $userId, $reminder);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
