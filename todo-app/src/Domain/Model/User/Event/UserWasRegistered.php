<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\User\UserEmail;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserPassword;

final class UserWasRegistered implements SerializablePayload
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var UserEmail
     */
    private $email;

    /**
     * @var UserPassword
     */
    private $password;

    /**
     * UserWasRegistered constructor.
     *
     * @param UserId       $userId
     * @param UserEmail    $email
     * @param UserPassword $password
     */
    public function __construct(UserId $userId, UserEmail $email, UserPassword $password)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new UserWasRegistered(
            UserId::fromString($payload['user_id']),
            UserEmail::fromString($payload['email']),
            UserPassword::fromString($payload['password'])
        );
    }

    public function toPayload(): array
    {
        return [
            'user_id' => $this->userId()->toString(),
            'email' => $this->email()->toString(),
            'password' => $this->password()->toString(),
        ];
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }
}
