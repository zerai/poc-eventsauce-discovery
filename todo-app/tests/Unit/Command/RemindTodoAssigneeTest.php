<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\RemindTodoAssignee;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;

class RemindTodoAssigneeTest extends TestCase
{
    private const REMINDER_DATE = '2050-01-01 10:00:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $todoReminder = TodoReminder::from(self::REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->toString());

        $command = RemindTodoAssignee::fromPayload([
            'todo_id' => $todoId->toString(),
            'reminder' => $todoReminder->toString(),
            'reminder_status' => $reminderStatus,
        ]);

        self::assertInstanceOf(RemindTodoAssignee::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
        self::assertTrue($todoReminder->sameValueAs($command->reminder()));
        self::assertEquals($command->reminderStatus()->toString(), $reminderStatus);
    }

    /** @test */
    public function can_return_the_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $todoReminder = TodoReminder::from(self::REMINDER_DATE, $reminderStatus = TodoReminderStatus::OPEN()->toString());

        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'reminder' => $todoReminder->toString(),
            'reminder_status' => $reminderStatus,
        ];

        $command = new RemindTodoAssignee($todoId, $todoReminder, TodoReminderStatus::fromName($reminderStatus));

        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
