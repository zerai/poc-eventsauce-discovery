<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\Decorator;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class YourDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('x-decorated-by', 'Frank de Jonge');
    }
}
