<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDone;
use TodoApp\Domain\Model\Todo\Command\MarkTodoAsDoneHandler;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\TodoNotOpen;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class MarkTodoAsDoneTest extends PostTodoTestCase
{
    protected function commandHandler(TodoRepository $repository)//, Clock $clock
    {
        return new MarkTodoAsDoneHandler($repository); //, $clock
    }

    /** @test */
    public function allow_mark_todo_as_done()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
           new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', $assignee = UserId::generate(), TodoStatus::OPEN())
        )->when(
            new MarkTodoAsDone($todoId)
        )->then(
            new TodoWasMarkedAsDone($todoId, TodoStatus::DONE(), $assignee)
        );
    }

    /** @test */
    public function mark_todo_as_done_when_status_is_not_open_throw_exception()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
            new TodoWasPosted($this->aggregateRootId(), 'irrelevant text', UserId::generate(), TodoStatus::EXPIRED())
        )->when(
            new MarkTodoAsDone($todoId)
        )->expectToFail(
            TodoNotOpen::triedStatus(TodoStatus::DONE(), $this->aggregateRootId())
        );
    }
}
