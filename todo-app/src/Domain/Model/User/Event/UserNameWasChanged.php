<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Event;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use TodoApp\Domain\Model\User\UserId;

class UserNameWasChanged implements SerializablePayload
{
    private $userId;

    private $userName;

    public function __construct(UserId $userId, string $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
    }

    public function toPayload(): array
    {
        return [
            'user_id' => $this->userId()->toString(),
            'user_name' => (string) $this->userName,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new UserNameWasChanged(
            UserId::fromString($payload['user_id']),
            (string) $payload['user_name']
        );
    }

    /**
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function userName(): string
    {
        return $this->userName;
    }
}
