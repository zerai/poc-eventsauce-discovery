<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsExpired;
use TodoApp\Domain\Model\Todo\TodoId;

class MarkTodoAsExpiredTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();

        $command = MarkTodoAsExpired::fromPayload([
            'todo_id' => $todoId->toString(),
        ]);

        self::assertInstanceOf(MarkTodoAsExpired::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
    }

    /** @test */
    public function can_be_created_from_static_method(): void
    {
        $todoId = TodoId::generate();

        $command = MarkTodoAsExpired::forTodo(
            $todoId
        );

        self::assertInstanceOf(MarkTodoAsExpired::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
        ];

        $command = new MarkTodoAsExpired($todoId);

        self::assertEquals($expectedPayload, $command->toPayload());
        self::assertInstanceOf(MarkTodoAsExpired::class, $command);
    }
}
