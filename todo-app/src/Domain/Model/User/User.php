<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

use EventSauce\EventSourcing\AggregateRoot;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;

class User implements AggregateRoot
{
    use UserAggregateRootBehaviourWithRequiredHistory;

    /** @var UserId */
    private $id;

    /** @var UserEmail */
    private $email;

    /** @var UserPassword */
    private $password;

    public static function register(
        UserId $userId,
        UserEmail $email,
        UserPassword $password
    ): User {
        $user = new self($userId);
        $user->recordThat(UserWasRegistered::fromPayload([
            'user_id' => $userId->toString(),
            'email' => $email->toString(),
            'password' => $password->toString(),
        ]));

        return $user;
    }

    /**
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @return UserEmail
     */
    public function email(): UserEmail
    {
        return $this->email;
    }

    /**
     * @return UserPassword
     */
    public function password(): UserPassword
    {
        return $this->password;
    }

    private function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->id = $event->userId();
        $this->email = $event->email();
        $this->password = $event->password();
    }
}
