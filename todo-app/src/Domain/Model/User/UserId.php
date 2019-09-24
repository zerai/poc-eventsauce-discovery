<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserId implements AggregateRootId
{
//    private $uuid;
//
//    public static function generate(): UserId
//    {
//        return new self(\Ramsey\Uuid\Uuid::uuid4());
//    }
//
//    public static function fromString(string $userId): AggregateRootId
//    {
//        return new self(\Ramsey\Uuid\Uuid::fromString($userId));
//    }
//
//    private function __construct(\Ramsey\Uuid\UuidInterface $userId)
//    {
//        $this->uuid = $userId;
//    }
//
//    public function toString(): string
//    {
//        return $this->uuid->toString();
//    }
//
//    public function __toString(): string
//    {
//        return $this->uuid->toString();
//    }
//
//    public function toUuid(): UuidInterface
//    {
//        return Uuid::fromString($this->uuid);
//    }
//
//    public function equals(UserId $other): bool
//    {
//        return $this->uuid->equals($other->uuid);
//    }

    /**
     * @var string
     */
    private $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    public function toUuid(): UuidInterface
    {
        return Uuid::fromString($this->identifier);
    }

    public static function create(): UserId
    {
        return new UserId(Uuid::uuid4()->toString());
    }

    public static function generate(): UserId
    {
        return new UserId(Uuid::uuid4()->toString());
    }

    /**
     * @param string $aggregateRootId
     *
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}
