<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserPassword;

class UserWasRegisteredTest extends TestCase
{
    /** @test */
    public function can_be_created_from_paylod(): void
    {
        $userId = UserId::generate();
        $email = UserEmail::fromString('irrelevant@example.com');
        $password = UserPassword::fromString('irrelevant');

        $event = UserWasRegistered::fromPayload([
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ]);

        self::assertInstanceOf(UserWasRegistered::class, $event);
        self::assertTrue($userId->equals($event->userId()));
        self::assertTrue($email->equals($event->email()));
        self::assertEquals($password, $event->password());
    }

    /** @test */
    public function it_return_ayload_as_array(): void
    {
        $userId = UserId::generate();
        $email = UserEmail::fromString('irrelevant@example.com');
        $password = UserPassword::fromString('irrelevant');
        $expectedPayload = [
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ];

        $event = UserWasRegistered::fromPayload([
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ]);

        self::assertEquals($expectedPayload, $event->toPayload());
        self::assertInstanceOf(UserWasRegistered::class, $event);
    }
}
