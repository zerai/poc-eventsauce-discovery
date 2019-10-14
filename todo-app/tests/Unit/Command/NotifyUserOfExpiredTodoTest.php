<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\NotifyUserOfExpiredTodo;
use TodoApp\Domain\Model\Todo\TodoId;

class NotifyUserOfExpiredTodoTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();

        $command = NotifyUserOfExpiredTodo::fromPayload([
            'todo_id' => $todoId->toString(),
        ]);

        self::assertInstanceOf(NotifyUserOfExpiredTodo::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
    }

    /** @test */
    public function can_be_created_from_named_constructor(): void
    {
        $todoId = TodoId::generate();

        $command = NotifyUserOfExpiredTodo::with(
            $todoId
        );

        self::assertInstanceOf(NotifyUserOfExpiredTodo::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
    }

    /** @test */
    public function can_return_the_payload_as_array(): void
    {
        $todoId = TodoId::generate();

        $expectedPayload = [
            'todo_id' => $todoId->toString(),
        ];

        $command = new NotifyUserOfExpiredTodo($todoId);

        self::assertInstanceOf(NotifyUserOfExpiredTodo::class, $command);
        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
