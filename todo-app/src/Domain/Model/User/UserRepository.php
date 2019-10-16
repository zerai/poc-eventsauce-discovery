<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

use TodoApp\Domain\Model\User\Exception\UserAlreadyExist;
use TodoApp\Domain\Model\User\Exception\UserNotFound;

interface UserRepository
{
    /**
     * @param User $user
     *
     * @throws UserAlreadyExist
     */
    public function addNewUser(User $user): void;

    /**
     * @param User $user
     *
     * @throws UserNotFound
     */
    public function save(User $user): void;

    /**
     * @param UserId $userId
     *
     * @return User|null
     *
     * @throws UserNotFound
     */
    public function ofId(UserId $userId): ?User;
}
