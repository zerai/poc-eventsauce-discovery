<?php

declare(strict_types=1);

namespace TodoApp\Domain\ProcessManager;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use League\Tactician\CommandBus;
use TodoApp\Domain\Model\Todo\Command\NotifyUserOfExpiredTodo;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsExpired;

class SendTodoDeadlineExpiredMailProcessManager implements Consumer
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * SendTodoDeadlineExpiredMailProcessManager constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Message $message)
    {
        $event = $message->event();

        if ($event instanceof TodoWasMarkedAsExpired) {
            $this->commandBus->handle(
                NotifyUserOfExpiredTodo::with($event->todoId())
            );
        }
    }
}
