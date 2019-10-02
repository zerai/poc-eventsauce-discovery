<?php

declare(strict_types=1);

namespace EventSauceTools\Projection;

use EventSauce\EventSourcing\Message;

abstract class AbstractProjection implements Projection
{
    protected const MAP = [];

    public function project(Message ...$messages)
    {
        foreach ($messages as $message) {
            $method = 'projectWhen'.(new \ReflectionClass(get_class($message->event())))->getShortName();
            $this->$method($message->event());
        }
    }

    public function supportTypes(): array
    {
        return self::MAP;
    }
}
