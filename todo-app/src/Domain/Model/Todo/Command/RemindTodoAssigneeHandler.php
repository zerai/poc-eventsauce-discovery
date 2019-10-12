<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Command;

use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoRepository;

final class RemindTodoAssigneeHandler
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * RemindTodoAssigneeHandler constructor.
     *
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param RemindTodoAssignee $commmand
     *
     * @throws \TodoApp\Domain\Model\Todo\Exception\InvalidReminder
     * @throws \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function __invoke(RemindTodoAssignee $commmand)
    {
        $todo = $this->todoRepository->ofId($commmand->todoId());

        $reminder = $todo->reminder();

        if ($this->reminderShouldBeProcessed($todo, $reminder)) {
            $todo->remindAssignee($reminder);
            $this->todoRepository->save($todo);
        }
    }

    // analisi.. ? parameter todo contiene già il reminder perchè passarlo separato? avrebbe senso se il todo fosse il reminder proveniente dall' oggetto command
    private function reminderShouldBeProcessed(Todo $todo, TodoReminder $reminder): bool
    {
        // drop command, wrong reminder
        if (!$todo->reminder()->sameValueAs($reminder)) {
            return false;
        }
        // drop command, reminder is closed
        if (!$reminder->isOpen()) {
            return false;
        }
        // drop command, reminder is in future
        if ($reminder->isInTheFuture()) {
            return false;
        }

        return true;
    }
}
