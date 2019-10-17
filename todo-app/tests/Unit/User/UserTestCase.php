<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;
use TodoApp\Domain\Model\User\UserRepository;

abstract class UserTestCase extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return UserId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return User::class;
    }

    protected function commandHandler(UserRepository $repository)//, Clock $clock
    {
        //return new PostTodoHandler($repository); //, $clock
    }

    protected function handle($command): void
    {
        $commandHandler = $this->commandHandler($this->setupUserRepository()); //, $this->clock()
        ($commandHandler)($command);
    }

    public function setupUserRepository(): UserRepository
    {
        return new \TodoApp\Infrastructure\EventSauce\UserRepository\UserRepository($this->repository);
    }
}
