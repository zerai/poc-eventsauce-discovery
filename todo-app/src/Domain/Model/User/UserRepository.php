<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

interface UserRepository
{
    public function store(User $user): void;

    public function ofId(UserId $userId): User;
}
