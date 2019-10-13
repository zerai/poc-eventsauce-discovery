<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\AddDeadlineToTodo;
use TodoApp\Domain\Model\Todo\TodoDeadline;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

class AddDeadlineToTodoTest extends TestCase
{
    private const FUTURE_DEADLINE = '2050-01-01 10:00:00';
    private const PAST_DEADLINE = '1900-01-01 10:00:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $deadline = TodoDeadline::fromString(self::FUTURE_DEADLINE);

        $command = AddDeadlineToTodo::fromPayload([
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'deadline' => $deadline->toString(),
        ]);

        self::assertInstanceOf(AddDeadlineToTodo::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
        self::assertTrue($userId->equals($command->userId()));
        // TODO
        //self::assertEquals($deadline->sameValueAs($command->deadline()));
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $userId = UserId::generate();
        $deadline = TodoDeadline::fromString(self::FUTURE_DEADLINE);
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'user_id' => $userId->toString(),
            'deadline' => $deadline->toString(),
        ];

        $command = new AddDeadlineToTodo($todoId, $userId, $deadline);

        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
