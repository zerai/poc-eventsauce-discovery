<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\DeadlineWasAddedToTodo;
use TodoApp\Domain\Model\Todo\TodoDeadline;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\User\UserId;

class DeadlineWasAddedToTodoTest extends TestCase
{
    private const FUTURE_DEADLINE = '2050-01-01 10:00:00';
    private const PAST_DEADLINE = '1900-01-01 10:00:00';

    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $deadline = TodoDeadline::fromString(self::FUTURE_DEADLINE);
        $userId = UserId::generate();

        $event = DeadlineWasAddedToTodo::fromPayload([
            'todo_id' => $todoId->toString(),
            'deadline' => $deadline->toString(),
            'user_id' => $userId->toString(),
        ]);

        self::assertInstanceOf(DeadlineWasAddedToTodo::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        //self::assertEquals($deadline->sameValueAs($event->deadline()));
        self::assertTrue($userId->equals($event->userId()));

        self::assertEquals($deadline->toString(), $event->deadline()->toString());
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $deadline = TodoDeadline::fromString(self::FUTURE_DEADLINE);
        $userId = UserId::generate();
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'deadline' => $deadline->toString(),
            'user_id' => $userId->toString(),
        ];

        $event = new DeadlineWasAddedToTodo($todoId, $userId, $deadline);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
