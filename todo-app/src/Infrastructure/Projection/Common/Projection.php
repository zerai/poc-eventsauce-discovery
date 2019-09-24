<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\Projection\Common;

use EventSauce\EventSourcing\Message;

interface Projection
{
    public function project(Message ...$events);
}
