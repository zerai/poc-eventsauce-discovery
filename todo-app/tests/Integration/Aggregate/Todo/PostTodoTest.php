<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Aggregate\Todo;

use TodoApp\Domain\Model\Todo\Command\PostTodo;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Exception\TodoAlreadyExist;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class PostTodoTest extends PostTodoTestCase
{
//    protected function commandHandler(TodoRepository $repository)//, Clock $clock
//    {
//        return new PostTodoHandler($repository); //, $clock
//    }

    /** @test */
    public function post_a_todo()
    {
        $todoId = $this->aggregateRootId();

        $this->when(
            new PostTodo($todoId, 'irrelevant text', $assigeeId = UserId::generate())
        )->then(
            new TodoWasPosted($todoId, 'irrelevant text', $assigeeId, TodoStatus::OPEN())
        );

        self::assertEquals('irrelevant text', $this->retrieveAggregateRoot($todoId)->todoText());
        self::assertEquals(TodoStatus::OPEN(), $this->retrieveAggregateRoot($todoId)->status());
    }

    /** @test */
    public function post_a_todo_twice_should_be_throw_exception()
    {
        $todoId = $this->aggregateRootId();

        $this->given(
           new TodoWasPosted($this->newAggregateRootId(), 'irrelevant text', UserId::generate(), TodoStatus::OPEN())
        )->when(
            new PostTodo($todoId, 'irrelevant text', $assigeeId = UserId::generate())
        )->expectToFail(
            TodoAlreadyExist::withTodoId($todoId)
        );
    }
}
