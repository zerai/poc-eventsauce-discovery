<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoWasPostedTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();
        $todoText = (string) 'irrelevant text';
        $assigneeId = UserId::generate();
        $status = TodoStatus::OPEN();

        $event = TodoWasPosted::fromPayload([
            'todo_id' => $todoId->toString(),
            'todo_text' => $todoText,
            'user_id' => $assigneeId->toString(),
            'status' => $status->toString(),
        ]);

        self::assertInstanceOf(TodoWasPosted::class, $event);
        self::assertTrue($todoId->equals($event->todoId()));
        self::assertEquals($todoText, $event->todoText());
        self::assertTrue($assigneeId->equals($event->assigneeId()));
        self::assertTrue($status->equals($event->status()));
    }

    /** @test */
    public function can_return_array_as_payload(): void
    {
        $todoId = TodoId::generate();
        $todoText = (string) 'irrelevant text';
        $assigneeId = UserId::generate();
        $status = TodoStatus::OPEN();
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
            'todo_text' => $todoText,
            'user_id' => $assigneeId->toString(),
            'status' => $status->toString(),
        ];

        $event = new TodoWasPosted($todoId, $todoText, $assigneeId, $status);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
