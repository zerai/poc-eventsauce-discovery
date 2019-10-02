<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\Decorator;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class EventAliasDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeaders([
            'x-request-id' => '4586',
            'x-request-user-id' => '333333',
        ]);
    }
}
