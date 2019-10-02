<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce;

use EventSauce\EventSourcing\AggregateRootRepository;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserRepository as UserRepositoryPort;

class UserRepository implements UserRepositoryPort
{
    /**
     * @var AggregateRootRepository
     */
    private $aggregateRootRepository;

    public function __construct(AggregateRootRepository $aggregateRootRepository)
    {
        $this->aggregateRootRepository = $aggregateRootRepository;
    }

    public function store(User $user): void
    {
        $this->aggregateRootRepository->persist($user);
    }

    public function ofId(UserId $userId): User
    {
        return $this->aggregateRootRepository->retrieve($userId);
    }
}
