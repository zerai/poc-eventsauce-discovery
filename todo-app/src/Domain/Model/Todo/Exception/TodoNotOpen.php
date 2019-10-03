<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoStatus;

final class TodoNotOpen extends \RuntimeException
{
    public static function triedStatus(TodoStatus $status, Todo $todo): TodoNotOpen
    {
        return new self(\sprintf(
            'Tried to change status of Todo %s to %s. But Todo is not marked as open!',
            $todo->id()->toString(),
            $status->toString()
        ));
    }

//    public static function triedToAddDeadline(TodoDeadline $deadline, TodoStatus $status): TodoNotOpen
//    {
//        return new self(\sprintf(
//            'Tried to deadline %s to a todo with status %s.',
//            $deadline->toString(),
//            $status->toString()
//        ));
//    }
//
//    public static function triedToAddReminder(TodoReminder $reminder, TodoStatus $status): TodoNotOpen
//    {
//        return new self(\sprintf(
//            'Tried to add reminder %s to a todo with status %s.',
//            $reminder->toString(),
//            $status->toString()
//        ));
//    }
//
//    public static function triedToSendReminder(TodoReminder $reminder, TodoStatus $status): TodoNotOpen
//    {
//        return new self(\sprintf(
//            'Tried to send a reminder %s for a todo with status %s.',
//            $reminder->toString(),
//            $status->toString()
//        ));
//    }
//
//    public static function triedToExpire(TodoStatus $status): TodoNotOpen
//    {
//        return new self(\sprintf('Tried to expire todo with status %s.', $status->toString()));
//    }
}
