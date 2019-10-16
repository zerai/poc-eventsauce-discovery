<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

final class UserEmail
{
    private $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email): UserEmail
    {
        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function equals(UserEmail $other): bool
    {
        return $this->email === $other->email;
    }
}
