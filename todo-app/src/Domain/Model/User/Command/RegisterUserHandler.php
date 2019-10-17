<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Command;

use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserRepository;

class RegisterUserHandler
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * RegisterUserHandler constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegisterUser $command
     *
     * @throws \TodoApp\Domain\Model\User\Exception\UserAlreadyExist
     */
    public function __invoke(RegisterUser $command): void
    {
        $user = User::register(
            $command->userId(),
            $command->email(),
            $command->password()
        );

        $this->userRepository->addNewUser($user);
    }
}
