<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasMarkedAsExpiredTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $oldStatus = TodoStatus::OPEN();
        $newStatus = TodoStatus::EXPIRED();
        $assigneeId = UserId::generate();

        $event = TodoWasMarkedAsExpired::fromPayload([
            'todo_id' => $todoId->toString(),
            'old_status' => $oldStatus->toString(),
            'new_status' => $newStatus->toString(),
            'assignee_id' => $assigneeId->toString(),
        ]);

        self::assertInstanceOf(TodoWasMarkedAsExpired::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($oldStatus->equals($event->oldStatus()));
        self::assertTrue($newStatus->equals($event->newStatus()));
        self::assertTrue($assigneeId->equals($event->assigneeId()));
    }

    /** @test */
    public function can_be_created_from_static_method(): void
    {
        $todoId = TodoId::generate();
        $oldStatus = TodoStatus::OPEN();
        $newStatus = TodoStatus::EXPIRED();
        $assigneeId = UserId::generate();

        $event = TodoWasMarkedAsExpired::fromStatus(
            $todoId,
            $oldStatus,
            $newStatus,
            $assigneeId
        );

        self::assertInstanceOf(TodoWasMarkedAsExpired::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($oldStatus->equals($event->oldStatus()));
        self::assertTrue($newStatus->equals($event->newStatus()));
        self::assertTrue($assigneeId->equals($event->assigneeId()));
    }

    /** @test */
    public function can_return_array_as_payload(): void
    {
        $todoId = TodoId::generate();
        $oldStatus = TodoStatus::OPEN();
        $newStatus = TodoStatus::EXPIRED();
        $assigneeId = UserId::generate();

        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'old_status' => $oldStatus->toString(),
            'new_status' => $newStatus->toString(),
            'assignee_id' => $assigneeId->toString(),
        ];

        $event = new TodoWasMarkedAsExpired($todoId, $oldStatus, $newStatus, $assigneeId);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
