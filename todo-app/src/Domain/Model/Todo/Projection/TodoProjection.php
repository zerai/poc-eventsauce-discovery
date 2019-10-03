<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Projection;

use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;

interface TodoProjection
{
    const PROJECTABLE_EVENTS = [
        TodoWasPosted::class,
        TodoWasMarkedAsDone::class,
    ];

    public function projectWhenTodoWasPosted(TodoWasPosted $event): void;

    public function projectWhenTodoWasMarkedAsDone(TodoWasMarkedAsDone $event): void;
}
