<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\Todo\Todo;

final class CannotReopenTodo extends \RuntimeException
{
    public static function notMarkedDone(Todo $todo): CannotReopenTodo
    {
        // TODO ? why we need all Todo object
        return new self(\sprintf(
            'Tried to reopen status of Todo %s. But Todo is not marked as done!',
            $todo->id()->toString()
        ));
    }
}
