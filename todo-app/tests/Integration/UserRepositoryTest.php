<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration;

use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;

class UserRepositoryTest extends KernelTestCase
{
    /** @var AggregateRootRepository */
    private $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->userRepository = self::$container->get('jphooiveld_eventsauce.aggregate_repository.user_repository');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_persist_a_user(): void
    {
        //self::markTestSkipped();
        $user = User::register(
            UserId::generate(),
            'my username',
            'irrelevant@example.com'
        );

        $this->userRepository->persist($user);
    }

    /** @test */
    public function it_can_retrive_a_user(): void
    {
        /** @var User $user */
        $user = User::register(
            $userId = UserId::generate(),
            'my username',
            'irrelevant@example.com'
        );

        $this->userRepository->persist($user);

        $retrivedUser = $this->userRepository->retrieve($userId);

        self::assertEquals($userId, $retrivedUser->id());
    }

    protected function tearDown(): void
    {
        $this->userRepository = null;
    }
}
