<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDone;
use TodoApp\Domain\Model\Todo\TodoId;

class MarkTodoAsDoneTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $todoId = TodoId::generate();

        $command = MarkTodoAsDone::fromPayload([
            'todo_id' => $todoId->toString(),
        ]);

        self::assertInstanceOf(MarkTodoAsDone::class, $command);
        self::assertTrue($todoId->equals($command->todoId()));
    }

    /** @test */
    public function can_return_payload_as_array(): void
    {
        $todoId = TodoId::generate();
        $expectedPayload = [
            'todo_id' => $todoId->toString(),
        ];

        $command = new MarkTodoAsDone($todoId);

        self::assertInstanceOf(MarkTodoAsDone::class, $command);
        self::assertEquals($expectedPayload, $command->toPayload());
    }
}
