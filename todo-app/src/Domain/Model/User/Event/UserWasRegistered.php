<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\User\UserId;

final class UserWasRegistered implements SerializablePayload
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $email;

    public function __construct(
        UserId $userId,
        string $userName,
        string $email
    ) {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->email = $email;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function email(): string
    {
        return $this->email;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new UserWasRegistered(
            UserId::fromString($payload['user_id']),
            (string) $payload['user_name'],
            (string) $payload['email']
        );
    }

    public function toPayload(): array
    {
        return [
            'user_id' => $this->userId()->toString(),
            'user_name' => (string) $this->userName,
            'email' => (string) $this->email,
        ];
    }
}
