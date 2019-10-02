<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use EventSauce\EventSourcing\AggregateRootId;

final class TodoId implements AggregateRootId
{
    private $uuid;

    public static function generate(): TodoId
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4());
    }

    public static function fromString(string $todoId): AggregateRootId
    {
        return new self(\Ramsey\Uuid\Uuid::fromString($todoId));
    }

    private function __construct(\Ramsey\Uuid\UuidInterface $todoId)
    {
        $this->uuid = $todoId;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(TodoId $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }
}
