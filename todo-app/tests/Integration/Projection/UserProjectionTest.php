<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Projection;

use EventSauce\EventSourcing\Message;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserPassword;
use TodoApp\Infrastructure\Projection\UserProjection;

class UserProjectionTest extends KernelTestCase
{
    private $projection;

    protected function setUp()
    {
        self::bootKernel();

        $this->projection = self::$container->get('TodoApp\Infrastructure\Projection\UserProjection');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_project_event()
    {
        $event = UserWasRegistered::fromPayload([
            'user_id' => Uuid::uuid4()->toString(),
            'email' => UserEmail::fromString('irrelevant@example.com')->toString(),
            'password' => UserPassword::fromString('irrelevant')->toString(),
        ]);

        $message = new Message($event);

        /** @var UserProjection $projection */
        $projection = $this->projection->project($message);
    }

    /** @test */
    public function it_can_reset_project()
    {
        self::markTestSkipped();
        /* @var UserProjection $projection */
        $this->projection->reset();
    }

    protected function tearDownUp()
    {
    }
}
