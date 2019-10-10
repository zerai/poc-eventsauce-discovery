<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoDeadline;

final class TodoNotExpired extends \RuntimeException
{
    public static function withDeadline(TodoDeadline $deadline, Todo $todo): TodoNotExpired
    {
        //TODO unused variable
        return new self(\sprintf(
            'Tried to mark a non-expired Todo as expired.  Todo will expire after the deadline %s.',
            $deadline->toString()
        ));
    }
}
