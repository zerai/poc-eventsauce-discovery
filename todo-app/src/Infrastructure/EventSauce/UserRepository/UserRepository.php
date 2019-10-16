<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\UserRepository;

use EventSauce\EventSourcing\AggregateRootRepository;
use TodoApp\Domain\Model\User\Exception\UserAlreadyExist;
use TodoApp\Domain\Model\User\Exception\UserNotFound;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserRepository as UserRepositoryPort;

class UserRepository implements UserRepositoryPort
{
    /**
     * @var AggregateRootRepository
     */
    private $userAggregateRepository;

    public function __construct(AggregateRootRepository $userAggregateRepository)
    {
        $this->userAggregateRepository = $userAggregateRepository;
    }

    /**
     * @param User $user
     *
     * @throws UserAlreadyExist
     */
    public function addNewUser(User $user): void
    {
        // TODO: UGLY UGLY UGLY!!!
        // maybe the trait TodoAggregateRootBehaviourWithRequiredHistory should be return 0 or a special object User::unknow
        try {
            $this->userAggregateRepository->retrieve($user->id());
        } catch (UserNotFound $throwable) {
            $this->userAggregateRepository->persist($user);

            return;
        }
        throw UserAlreadyExist::withUserId($user->id());
    }

    /**
     * @param User $user
     *
     * @throws UserNotFound
     */
    public function save(User $user): void
    {
        if ($this->userAggregateRepository->retrieve($user->id())) {
            $this->userAggregateRepository->persist($user);
        }
    }

    /**
     * @param UserId $userId
     *
     * @return User|null
     *
     * @throws UserNotFound
     */
    public function ofId(UserId $userId): ?User
    {
        return $this->userAggregateRepository->retrieve($userId);
    }
}
