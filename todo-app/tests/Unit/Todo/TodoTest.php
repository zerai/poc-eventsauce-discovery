<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Todo;

use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\TodoNotOpen;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoTest extends TodoTestCase
{
    /** @test */
    public function post_a_todo()
    {
        $todoId = $this->aggregateRootId();

        $userId = UserId::generate();
        $status = TodoStatus::fromName('OPEN');

        $this->whenMethod('post',
            $todoId,
            'todo text',
            $userId,
            $status
        )->then(
            new TodoWasPosted($todoId, 'todo text', $userId, $status)
        );
    }

    /** @test */
    public function mark_a_todo_as_done()
    {
        $todoId = TodoId::generate();
        $assigeeId = UserId::generate();
        $newStatus = TodoStatus::DONE();

        $this->given(
            new TodoWasPosted($todoId, 'todo text', $assigeeId, TodoStatus::OPEN())
        )->whenMethod(
            'markAsDone'
        )->then(
            new TodoWasMarkedAsDone($todoId, $newStatus, $assigeeId)
        );

        //self::assertEquals($newStatus, $this->retriveTodoByid($todoId)->status());
    }

    /** @test */
    public function throw_exception_when_mark_a_todo_as_done()
    {
        self::markTestSkipped();
        $todoId = TodoId::generate();
        $assigeeId = UserId::generate();
        $newStatus = TodoStatus::EXPIRED();

        $this->given(
            new TodoWasPosted($todoId, 'todo text', $assigeeId, TodoStatus::EXPIRED())
        )->whenMethod(
            'markAsDone'
        )->expectToFail(
            TodoNotOpen::triedStatus($newStatus, $todoId)
        );
    }
}
