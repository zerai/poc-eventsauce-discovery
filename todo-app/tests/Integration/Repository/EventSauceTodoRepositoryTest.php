<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Repository;

use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Domain\Model\User\UserId;

class EventSauceTodoRepositoryTest extends KernelTestCase
{
    /** @var AggregateRootRepository */
    private $todoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->todoRepository = self::$container->get('eventsauce.aggregate_repository.todo');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_persist_a_todo(): void
    {
        //self::markTestSkipped();
        $todo = Todo::post(
            TodoId::generate(),
            'my todo text',
            UserId::generate(),
            TodoStatus::OPEN()
        );

        $this->todoRepository->persist($todo);
    }

    /** @test */
    public function it_can_retrive_a_todo(): void
    {
        /** @var Todo $todo */
        $todo = Todo::post(
            $todoId = TodoId::generate(),
            'my todo text',
            UserId::generate(),
            TodoStatus::OPEN()
        );

        $this->todoRepository->persist($todo);

        $retrivedTodo = $this->todoRepository->retrieve($todoId);

        self::assertEquals($todoId, $retrivedTodo->id());
    }

    protected function tearDown(): void
    {
        $this->todoRepository = null;
    }
}
