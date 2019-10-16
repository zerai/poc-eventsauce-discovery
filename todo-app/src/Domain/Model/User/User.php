<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

use EventSauce\EventSourcing\AggregateRoot;
use TodoApp\Domain\Model\User\Event\UserNameWasChanged;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;

class User implements AggregateRoot
{
    use UserAggregateRootBehaviourWithRequiredHistory;

    /** @var UserId */
    private $id;

    /** @var string */
    private $userName;

    /** @var string */
    private $email;

    public static function register(
        UserId $userId,
        string $userName,
        string $email
    ): User {
        $user = new self($userId);
        $user->recordThat(UserWasRegistered::fromPayload([
            'user_id' => $userId->toString(),
            'user_name' => $userName,
            'email' => $email,
        ]));

        return $user;
    }

    public function changeUserName(UserId $userId, string $userName): void
    {
        $this->recordThat(UserNameWasChanged::fromPayload([
            'user_id' => $userId->toString(),
            'user_name' => $userName,
        ]));
    }

    /**
     * @return UserId
     */
    public function id(): UserId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function userName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    private function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->id = $event->userId();
        $this->userName = $event->userName();
        $this->email = $event->email();
    }

    private function applyUserNameWasChanged(UserNameWasChanged $event): void
    {
        $this->userName = $event->userName();
    }
}
