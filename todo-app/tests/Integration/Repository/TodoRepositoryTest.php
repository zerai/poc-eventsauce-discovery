<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoRepository;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class TodoRepositoryTest extends KernelTestCase
{
    /** @var TodoRepository */
    private $todoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->todoRepository = self::$container->get('TodoApp\Infrastructure\EventSauce\TodoRepository\TodoRepository');
    }

    /** @test
     * @doesNotPerformAssertions
     */
    public function it_can_add_a_new_todo(): void
    {
        $this->todoRepository
            ->addNewTodo(
                Todo::post(TodoId::generate(), 'irrelevant todo', UserId::generate(), TodoStatus::DONE())
            );
    }

    /**
     * @test
     * @expectedException \TodoApp\Domain\Model\Todo\Exception\TodoAlreadyExist
     */
    public function it_cant_add_a_new_todo_if_already_exist(): void
    {
        $todo = Todo::post(TodoId::generate(), 'irrelevant todo', UserId::generate(), TodoStatus::DONE());

        $this->todoRepository
            ->addNewTodo($todo);

        $this->todoRepository
            ->addNewTodo($todo);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_save_a_todo(): void
    {
        $todo = Todo::post(TodoId::generate(), 'irrelevant todo', UserId::generate(), TodoStatus::DONE());

        $this->todoRepository
            ->addNewTodo($todo);

        $this->todoRepository
            ->save($todo);
    }

    /**
     * @test
     * @expectedException  \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function it_cant_save_a_todo_if_not_exist(): void
    {
        $this->todoRepository
            ->save(
                Todo::post(TodoId::generate(), 'irrelevant todo', UserId::generate(), TodoStatus::DONE())
            );
    }

    /** @test */
    public function it_can_retrive_a_todo_by_id(): void
    {
        $todo = Todo::post($todoId = TodoId::generate(), 'irrelevant todo', UserId::generate(), TodoStatus::DONE());

        $this->todoRepository
            ->addNewTodo($todo);

        $retrivedTodo = $this->todoRepository->ofId($todoId);

        self::assertEquals($todoId, $retrivedTodo->id());
    }

    /**
     * @test
     * @expectedException  \TodoApp\Domain\Model\Todo\Exception\TodoNotFound
     */
    public function it_cant_retrive_a_todo_by_id(): void
    {
        $todoId = TodoId::generate();

        $this->todoRepository->ofId($todoId);
    }

    protected function tearDown(): void
    {
        $this->todoRepository = null;
    }
}
