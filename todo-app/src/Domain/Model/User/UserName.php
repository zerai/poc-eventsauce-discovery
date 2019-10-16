<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

final class UserName
{
    private $userName;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    public static function fromString(string $userName): UserName
    {
        return new self($userName);
    }

    public function toString(): string
    {
        return $this->userName;
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function equals(UserName $other): bool
    {
        return $this->userName === $other->userName;
    }
}
