<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

class TodoDeadline
{
    /**
     * @var \DateTimeImmutable
     */
    private $deadline;

    /**
     * @var \DateTimeImmutable
     */
    private $createdOn;

    public static function fromString(string $deadline): TodoDeadline
    {
        return new self($deadline);
    }

    private function __construct(string $deadline)
    {
        $this->deadline = new \DateTimeImmutable($deadline, new \DateTimeZone('UTC'));
        $this->createdOn = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function isInThePast(): bool
    {
        return $this->deadline < $this->createdOn;
    }

    public function toString(): string
    {
        return $this->deadline->format(\DateTime::ATOM);
    }

    public function createdOn(): string
    {
        return $this->createdOn->format(\DateTime::ATOM);
    }

    public function isMet(): bool
    {
        return $this->deadline > new \DateTimeImmutable();
    }

    public function sameValueAs(TodoDeadline $object): bool
    {
        return \get_class($this) === \get_class($object)
            && $this->deadline->format('U.u') === $object->deadline->format('U.u')
            && $this->createdOn->format('U.u') === $object->createdOn->format('U.u');
    }
}
