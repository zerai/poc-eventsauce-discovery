<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use Psr\Log\LoggerInterface;

class SendTodoReminderMailHandler
{
    private $queryBus;

    private $mailer;

    /** @var LoggerInterface */
    private $logger;

    /**
     * NotifyUserOfExpiredTodoHandler constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(SendTodoReminderMail $command)
    {
        // TODO: get todo data or exit

        // TODO: get user data or exit

        // TODO: Prepare Mail Message

        // TODO: send message

        $this->logger->info(
            sprintf(
                'Hi %s! Just a heads up: your todo `%s` has expired on %s.',
                'fake username',
                'fake todo text',
                'fake deadline'
            )
        );
    }
}
