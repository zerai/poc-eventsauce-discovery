<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\User\UserId;

final class InvalidReminder extends \Exception
{
    public static function userIsNotAssignee(UserId $user, UserId $assigneeId): InvalidReminder
    {
        return new self(\sprintf(
            'User %s tried to add a reminder to the todo owned by %s',
            $user->toString(),
            $assigneeId->toString()
        ));
    }

    public static function reminderInThePast(TodoReminder $reminder): InvalidReminder
    {
        return new self(\sprintf(
            'Provided reminder %s is in the past',
            $reminder->toString()
        ));
    }

    public static function reminderInTheFuture(TodoReminder $reminder): InvalidReminder
    {
        return new self(\sprintf(
            'Provided reminder %s is in the future',
            $reminder->toString()
        ));
    }

    public static function alreadyReminded(): InvalidReminder
    {
        return new self('The assignee was already reminded.');
    }

    public static function reminderNotCurrent(TodoReminder $expected, TodoReminder $actual): InvalidReminder
    {
        return new self(\sprintf(
            'Notification for reminder %s can not be send, because %s is the current one.',
            $actual->toString(),
            $expected->toString()
        ));
    }
}
