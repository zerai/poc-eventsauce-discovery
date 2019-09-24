<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\ClassNameInflector;

use TodoApp\Domain\Model\User\Event\UserWasRegistered;

class TodoAppEventNameMap implements NameMap
{
    private const MAP = [
        UserWasRegistered::class => 'todo-app.user-was-registered',
    ];

    public function supportTypes(): array
    {
        return self::MAP;
    }
}
