<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User\Command;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\Command\RegisterUser;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserPassword;

class RegisterUserTest extends TestCase
{
    /** @test */
    public function can_be_create_from_payload(): void
    {
        $userId = UserId::generate();
        $email = UserEmail::fromString('irrelevant@example.com');
        $password = UserPassword::fromString('pippo');

        $command = RegisterUser::fromPayload([
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ]);

        self::assertInstanceOf(RegisterUser::class, $command);
        self::assertTrue($userId->equals($command->userId()));
        self::assertTrue($email->equals($command->email()));
        self::assertEquals($password, $command->password());
    }

    /** @test */
    public function it_return_payload_as_array(): void
    {
        $userId = UserId::generate();
        $email = UserEmail::fromString('irrelevant@example.com');
        $password = UserPassword::fromString('pippo');
        $expectedPayload = [
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ];

        $command = RegisterUser::fromPayload([
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ]);

        self::assertEquals($expectedPayload, $command->toPayload());
        self::assertInstanceOf(RegisterUser::class, $command);
    }
}
