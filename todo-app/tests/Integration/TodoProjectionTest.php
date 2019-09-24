<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration;

use EventSauce\EventSourcing\Message;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Projection\TodoProjection;
use TodoApp\Domain\Model\Todo\TodoStatus;
use TodoApp\Infrastructure\Projection\Common\Projection;

class TodoProjectionTest extends KernelTestCase
{
    private $projection;

    protected function setUp()
    {
        self::bootKernel();

        $this->projection = self::$container->get('TodoApp\Infrastructure\Projection\TodoProjection');
    }

    /** @test */
    public function it_project_event()
    {
        //self::markTestSkipped();
        $event = TodoWasPosted::fromPayload([
            'todo_id' => Uuid::uuid4()->toString(),
            'todo_text' => 'irrelevant text',
            'user_id' => Uuid::uuid4()->toString(),
            'status' => TodoStatus::fromName('OPEN')->toString(),
        ]);

        $message = new Message($event);

        /** @var TodoProjection $projection */
        $projection = $this->projection->project($message);
        //$projection = $this->projection->projectWhenUserSubscribedToMailingList($event);
    }

    /** @test */
    public function it_can_reset_project()
    {
        self::markTestSkipped();
        /* @var TodoProjection $projection */
        $this->projection->reset();
    }

    protected function tearDownUp()
    {
    }
}
