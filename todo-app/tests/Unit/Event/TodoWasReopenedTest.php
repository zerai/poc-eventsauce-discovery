<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\TodoWasReopened;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasReopenedTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $status = TodoStatus::OPEN();
        $assigneeId = UserId::generate();

        $event = TodoWasReopened::fromPayload([
            'todo_id' => $todoId->toString(),
            'status' => $status->toString(),
            'assignee_id' => $assigneeId->toString(),
        ]);

        self::assertInstanceOf(TodoWasReopened::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($status->equals($event->status()));
        self::assertTrue($assigneeId->equals($event->assigneeId()));
    }

    /** @test */
    public function can_return_array_as_payload(): void
    {
        $todoId = TodoId::generate();
        $status = TodoStatus::OPEN();
        $assigneeId = UserId::generate();
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'status' => $status->toString(),
            'assignee_id' => $assigneeId->toString(),
        ];

        $event = new TodoWasReopened($todoId, $status, $assigneeId);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
