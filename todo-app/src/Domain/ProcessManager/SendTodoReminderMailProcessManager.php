<?php

declare(strict_types=1);

namespace TodoApp\Domain\ProcessManager;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use League\Tactician\CommandBus;
use TodoApp\Domain\Model\Todo\Command\SendTodoReminderMail;
use TodoApp\Domain\Model\Todo\Event\TodoAssigneeWasReminded;

class SendTodoReminderMailProcessManager implements Consumer
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

        if ($event instanceof TodoAssigneeWasReminded) {
            $this->commandBus->handle(
                SendTodoReminderMail::with($event->userId(), $event->todoId())
            );
        }
    }
}
