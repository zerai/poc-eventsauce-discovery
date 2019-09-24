<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\Projection\Common;

use EventSauce\EventSourcing\Message;

abstract class AbstractProjection implements Projection
{
    public function project(Message ...$messages)
    {
        foreach ($messages as $message) {
            $method = 'projectWhen'.(new \ReflectionClass(get_class($message->event())))->getShortName();
            $this->$method($message->event());
        }
    }
}
