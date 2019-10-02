<?php

declare(strict_types=1);

namespace EventSauceTools\Projection;

use EventSauce\EventSourcing\Message;

interface Projection
{
    public function project(Message ...$events);
}
