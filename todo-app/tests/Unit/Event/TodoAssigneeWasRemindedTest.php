<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\TodoAssigneeWasReminded;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\User\UserId;

class TodoAssigneeWasRemindedTest extends TestCase
{
    private const REMINDER_DATE = '2050-01-01 10:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::REMINDER_DATE, 'OPEN');

        $event = TodoAssigneeWasReminded::fromPayload([
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
            'reminder_status' => $reminder->status()->toString(),
        ]);

        self::assertInstanceOf(TodoAssigneeWasReminded::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($userId->equals($event->userId()));
        self::assertTrue($reminder->sameValueAs($event->reminder()));
    }

    /** @test */
    public function can_return_the_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::REMINDER_DATE, 'OPEN');
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
            'reminder_status' => $reminder->status()->toString(),
        ];

        $event = new TodoAssigneeWasReminded($todoId, $userId, $reminder);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
