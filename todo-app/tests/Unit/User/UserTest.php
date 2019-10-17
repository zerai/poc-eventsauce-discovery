<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use TodoApp\Domain\Model\User\Command\RegisterUser;
use TodoApp\Domain\Model\User\Command\RegisterUserHandler;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;
use TodoApp\Domain\Model\User\Exception\UserAlreadyExist;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserPassword;
use TodoApp\Domain\Model\User\UserRepository;

class UserTest extends UserTestCase
{
    protected function commandHandler(UserRepository $repository)//, Clock $clock
    {
        return new RegisterUserHandler($repository);
    }

    /** @test */
    public function allow_register_a_user(): void
    {
        $this->when(
            new RegisterUser($userId = $this->aggregateRootId(), $email = UserEmail::fromString('irrelevant@email.com'), $password = UserPassword::fromString('irrelevant'))
        )->then(
            //UserWasRegistered::fromPayload()
            new UserWasRegistered($userId, $email, $password)
        );
    }

    /** @test */
    public function deny_register_a_user_if_user_already_exist(): void
    {
        $this->given(
            new UserWasRegistered($userId = $this->aggregateRootId(), $email = UserEmail::fromString('irrelevant@email.com'), $password = UserPassword::fromString('irrelevant'))
        )->when(
            new RegisterUser($userId = $this->aggregateRootId(), $email, $password)
        )->expectToFail(
            UserAlreadyExist::withUserId($userId)
        );
    }
}
