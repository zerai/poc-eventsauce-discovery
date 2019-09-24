<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\Decorator;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class EventAliasDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        // TODO: Implement decorate() method.
//        return $message->withHeader('x-decorated-event-name', 'fake.event.name');

        return $message->withHeaders([
            '__event_type' => 'fake.event.name',
            'x-request-id' => '4586',
            'x-request-user-id' => '333333',
        ]);
    }
}
