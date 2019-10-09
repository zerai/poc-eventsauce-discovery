<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\UserRepository;

use EventSauce\EventSourcing\AggregateRootRepository;
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

    public function store(User $user): void
    {
        $this->userAggregateRepository->persist($user);
    }

    public function ofId(UserId $userId): User
    {
        return $this->userAggregateRepository->retrieve($userId);
    }
}
