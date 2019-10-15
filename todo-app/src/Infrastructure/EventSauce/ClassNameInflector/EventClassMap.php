<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\ClassNameInflector;

use EventSauceTools\ClassMapInflector\ClassMap;
use TodoApp\Domain\Model\Todo\Event\DeadlineWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\ReminderWasAddedToTodo;
use TodoApp\Domain\Model\Todo\Event\TodoAssigneeWasReminded;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Event\TodoWasReopened;
use TodoApp\Domain\Model\Todo\Event\TodoWasUnmarkedAsExpired;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;

class EventClassMap implements ClassMap
{
    private const MAP = [
        DeadlineWasAddedToTodo::class => 'todo.deadline-was-added',
        ReminderWasAddedToTodo::class => 'todo.reminder-was-added',
        TodoAssigneeWasReminded::class => 'todo.todo-assignee-was-reminded',
        TodoWasMarkedAsDone::class => 'todo.todo-was-marked-as-done',
        TodoWasMarkedAsExpired::class => 'todo.todo-was-expired',
        TodoWasPosted::class => 'todo.todo-was-posted',
        TodoWasReopened::class => 'todo.todo-was-reopened',
        TodoWasUnmarkedAsExpired::class => 'todo.todo-was-unmarked-as-expired',
        //user event here...
        UserWasRegistered::class => 'todo.user-was-registered',
    ];

    public function supportClass(): array
    {
        return self::MAP;
    }
}
