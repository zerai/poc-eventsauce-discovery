<?php

declare(strict_types=1);

namespace TodoApp\Tests\Integration\Repository;

use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserPassword;

class UserRepositoryTest extends KernelTestCase
{
    /** @var AggregateRootRepository */
    private $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->userRepository = self::$container->get('eventsauce.aggregate_repository.user');
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
            UserEmail::fromString('irrelevant@example.com'),
            UserPassword::fromString('irrelevant')
        );

        $this->userRepository->persist($user);
    }

    /** @test */
    public function it_can_retrive_a_user(): void
    {
        /** @var User $user */
        $user = User::register(
            $userId = UserId::generate(),
            UserEmail::fromString('irrelevant@example.com'),
            UserPassword::fromString('irrelevant')
        );

        $this->userRepository->persist($user);

        $retrievedUser = $this->userRepository->retrieve($userId);

        self::assertEquals($userId, $retrievedUser->id());
    }

    protected function tearDown(): void
    {
        $this->userRepository = null;
    }
}
