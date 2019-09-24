<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use TodoApp\Domain\Model\User\Event\UserNameWasChanged;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;

class UserTest extends UserTestCase
{
    /** @test */
    public function initiating_a_user_register_process()
    {
        $userId = $this->aggregateRootId();

        $this->whenMethod('register',
             $userId,
                'j.doe',
            'jdoe@example.com'
        )->then(new UserWasRegistered($userId, 'j.doe', 'jdoe@example.com'));

        self::assertEquals($userId, $this->repository->retrieve($userId)->id());
        self::assertEquals('j.doe', $this->repository->retrieve($userId)->username());
        self::assertEquals('jdoe@example.com', $this->repository->retrieve($userId)->email());
    }

    /** @test */
    public function initiating_a_user_change_username_process()
    {
        $userId = $this->aggregateRootId();

        $this->given(
            new UserWasRegistered($userId, 'old name', 'old email')
        )->whenMethod(
            'changeUsername',
            $userId,
            'my new username'
        )->then(
            new UserNameWasChanged($userId, 'my new username')
        );

        self::assertEquals('my new username', $this->retriveUserByid($userId)->userName());
    }
}
