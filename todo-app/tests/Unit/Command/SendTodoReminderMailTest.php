<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\SendTodoReminderMail;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

class SendTodoReminderMailTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();

        $command = SendTodoReminderMail::fromPayload([
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
        ]);

        self::assertInstanceOf(SendTodoReminderMail::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
        self::assertTrue($userId->equals($command->userId()));
    }

    /** @test */
    public function can_be_created_from_named_constructor(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();

        $command = SendTodoReminderMail::with(
            $userId,
            $todoId
        );

        self::assertInstanceOf(SendTodoReminderMail::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
        self::assertTrue($userId->equals($command->userId()));
    }

    /** @test */
    public function can_return_the_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();

        $expectedPayload = [
            'user_id' => $todoId->toString(),
            'todo_id' => $todoId->toString(),
        ];

        $command = new SendTodoReminderMail($userId, $todoId);

        self::assertInstanceOf(SendTodoReminderMail::class, $command);
        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
