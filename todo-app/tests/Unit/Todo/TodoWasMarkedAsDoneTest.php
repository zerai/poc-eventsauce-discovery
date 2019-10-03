<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Todo;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasMarkedAsDoneTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $newStatus = TodoStatus::DONE();
        $assigneeId = UserId::generate();

        $event = TodoWasMarkedAsDone::fromPayload([
            'todo_id' => $todoId->toString(),
            'new_status' => $newStatus->toString(),
            'assignee_id' => $assigneeId->toString(),
        ]);

        self::assertInstanceOf(TodoWasMarkedAsDone::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($newStatus->equals($event->newStatus()));
        self::assertTrue($assigneeId->equals($event->assigneeId()));
    }

    /** @test */
    public function can_be_created_from_static_method(): void
    {
        $todoId = TodoId::generate();
        $newStatus = TodoStatus::DONE();
        $assigneeId = UserId::generate();

        $event = new TodoWasMarkedAsDone($todoId, $newStatus, $assigneeId);

        self::assertInstanceOf(TodoWasMarkedAsDone::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertTrue($newStatus->equals($event->newStatus()));
        self::assertTrue($assigneeId->equals($event->assigneeId()));
    }
}
