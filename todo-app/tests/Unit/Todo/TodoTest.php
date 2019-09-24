<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Todo;

use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
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
        )->then(new TodoWasPosted($todoId, 'todo text', $userId, $status));
    }
}
