<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\AddReminderToTodo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\User\UserId;

class AddReminderToTodoTest extends TestCase
{
    private const FUTURE_DEADLINE = '2050-01-01 10:00:00';
    private const PAST_DEADLINE = '1900-01-01 10:00:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::FUTURE_DEADLINE, 'OPEN');

        $command = AddReminderToTodo::fromPayload([
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
        ]);

        self::assertInstanceOf(AddReminderToTodo::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
        self::assertTrue($userId->equals($command->userId()));

        // TODO
        //self::assertEquals($reminder->sameValueAs($command->reminder()));
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $reminder = TodoReminder::from(self::FUTURE_DEADLINE, 'OPEN');
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'reminder' => $reminder->toString(),
        ];

        $command = new AddReminderToTodo($todoId, $userId, $reminder);

        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
